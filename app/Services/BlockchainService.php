<?php

namespace App\Services;

use App\Models\BlockchainBlock;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BlockchainService
{
    /**
     * The genesis block hash (hardcoded starting point of our chain)
     */
    const GENESIS_HASH = '0000000000000000000000000000000000000000000000000000000000000000';

    /**
     * Mine a new block for a certificate and add it to the chain.
     */
    public function mineBlock(Certificate $certificate): BlockchainBlock
    {
        return DB::transaction(function () use ($certificate) {
            // Get last block in chain
            $lastBlock = BlockchainBlock::orderBy('block_index', 'desc')->first();

            $blockIndex = $lastBlock ? $lastBlock->block_index + 1 : 1;
            $previousHash = $lastBlock ? $lastBlock->block_hash : self::GENESIS_HASH;
            $minedAt = now();

            // Build the data snapshot (what we hash)
            $blockData = [
                'certificate_id'    => $certificate->certificate_id,
                'student_name'      => $certificate->student_name,
                'enrollment_number' => $certificate->enrollment_number,
                'student_email'     => $certificate->student_email,
                'event_id'          => $certificate->event_id,
                'event_name'        => $certificate->event->name,
                'issued_by'         => $certificate->issuer->name,
                'issued_date'       => $certificate->issued_date->toDateString(),
                'achievement'       => $certificate->achievement,
            ];

            // Compute data hash (hash of the certificate content)
            $dataHash = hash('sha256', json_encode($blockData));

            // Compute block hash (hash of index + prev + data + timestamp)
            $blockHash = hash('sha256',
                $blockIndex . $previousHash . $dataHash . $minedAt->timestamp
            );

            $block = BlockchainBlock::create([
                'block_index'     => $blockIndex,
                'certificate_id'  => $certificate->id,
                'certificate_uid' => $certificate->certificate_id,
                'previous_hash'   => $previousHash,
                'data_hash'       => $dataHash,
                'block_hash'      => $blockHash,
                'block_data'      => $blockData,
                'mined_at'        => $minedAt,
            ]);

            Log::info("Block #{$blockIndex} mined for certificate {$certificate->certificate_id}");

            return $block;
        });
    }

    /**
     * Verify a single certificate's blockchain integrity.
     * Returns array with status and details.
     */
    public function verifyCertificate(Certificate $certificate): array
    {
        $block = $certificate->blockchainBlock;

        if (!$block) {
            return [
                'valid'   => false,
                'status'  => 'NO_BLOCK',
                'message' => 'No blockchain record found for this certificate.',
            ];
        }

        if ($certificate->status === 'revoked') {
            return [
                'valid'   => false,
                'status'  => 'REVOKED',
                'message' => 'This certificate has been revoked.',
                'block'   => $block,
            ];
        }

        // Step 1: Verify the block's own hash integrity
        if (!$block->isIntact()) {
            return [
                'valid'   => false,
                'status'  => 'TAMPERED',
                'message' => 'Block hash mismatch! Certificate data may have been tampered with.',
                'block'   => $block,
            ];
        }

        // Step 2: Verify previous block's hash still matches
        if ($block->block_index > 1) {
            $prevBlock = BlockchainBlock::where('block_index', $block->block_index - 1)->first();
            if (!$prevBlock || $prevBlock->block_hash !== $block->previous_hash) {
                return [
                    'valid'   => false,
                    'status'  => 'CHAIN_BROKEN',
                    'message' => 'Blockchain chain integrity broken! Previous block hash mismatch.',
                    'block'   => $block,
                ];
            }
        }

        // Step 3: Re-verify the data hash against current certificate data
        $currentData = [
            'certificate_id'    => $certificate->certificate_id,
            'student_name'      => $certificate->student_name,
            'enrollment_number' => $certificate->enrollment_number,
            'student_email'     => $certificate->student_email,
            'event_id'          => $certificate->event_id,
            'event_name'        => $certificate->event->name,
            'issued_by'         => $certificate->issuer->name,
            'issued_date'       => $certificate->issued_date->toDateString(),
            'achievement'       => $certificate->achievement,
        ];

        $currentDataHash = hash('sha256', json_encode($currentData));

        if ($currentDataHash !== $block->data_hash) {
            return [
                'valid'   => false,
                'status'  => 'DATA_MISMATCH',
                'message' => 'Certificate data hash mismatch! Data may have been modified.',
                'block'   => $block,
            ];
        }

        return [
            'valid'       => true,
            'status'      => 'VERIFIED',
            'message'     => 'Certificate is authentic and blockchain-verified.',
            'block'       => $block,
            'certificate' => $certificate,
        ];
    }

    /**
     * Validate the entire blockchain for integrity.
     */
    public function validateChain(): array
    {
        $blocks = BlockchainBlock::orderBy('block_index')->get();
        $errors = [];
        $previousHash = self::GENESIS_HASH;

        foreach ($blocks as $block) {
            // Check block hash
            if (!$block->isIntact()) {
                $errors[] = "Block #{$block->block_index} has invalid hash (tampered).";
            }

            // Check chain linkage
            if ($block->previous_hash !== $previousHash) {
                $errors[] = "Block #{$block->block_index} has broken chain link.";
            }

            $previousHash = $block->block_hash;
        }

        return [
            'valid'        => empty($errors),
            'total_blocks' => $blocks->count(),
            'errors'       => $errors,
        ];
    }

    /**
     * Generate a unique certificate ID
     */
    public function generateCertificateId(string $eventPrefix = 'CERT'): string
    {
        $year = date('Y');
        $unique = strtoupper(substr(uniqid(), -6)) . rand(10, 99);
        return "{$eventPrefix}-{$year}-{$unique}";
    }
}
