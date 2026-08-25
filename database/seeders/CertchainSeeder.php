<?php

namespace Database\Seeders;

use App\Models\CertificateTemplate;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class CertchainSeeder extends Seeder
{
    public function run(): void
    {
        // ── Roles ──────────────────────────────────────────
        $roles = ['admin', 'hod', 'faculty', 'coordinator'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // ── Admin User ─────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@college.edu'],
            [
                'name'        => 'System Administrator',
                'password'    => bcrypt('Admin@1234'),
                'employee_id' => 'ADM001',
                'department'  => 'Administration',
                'designation' => 'System Admin',
                'is_active'   => true,
            ]
        );
        $admin->assignRole('admin');

        // ── Sample HOD ─────────────────────────────────────
        $hod = User::firstOrCreate(
            ['email' => 'hod.cs@college.edu'],
            [
                'name'        => 'Dr. Priya Singh',
                'password'    => bcrypt('Hod@1234'),
                'employee_id' => 'HOD001',
                'department'  => 'Computer Science',
                'designation' => 'Head of Department',
                'is_active'   => true,
            ]
        );
        $hod->assignRole('hod');

        // ── Sample Faculty ─────────────────────────────────
        $faculty = User::firstOrCreate(
            ['email' => 'faculty@college.edu'],
            [
                'name'        => 'Prof. Amit Verma',
                'password'    => bcrypt('Faculty@1234'),
                'employee_id' => 'FAC001',
                'department'  => 'Computer Science',
                'designation' => 'Assistant Professor',
                'is_active'   => true,
            ]
        );
        $faculty->assignRole('faculty');

        // ── Default Certificate Template ───────────────────
        CertificateTemplate::firstOrCreate(
            ['name' => 'Participation Certificate'],
            [
                'type'         => 'participation',
                'border_style' => 'classic',
                'is_active'    => true,
                'created_by'   => $admin->id,
                'html_content' => $this->getDefaultTemplate(),
            ]
        );

        CertificateTemplate::firstOrCreate(
            ['name' => 'Achievement Certificate'],
            [
                'type'         => 'achievement',
                'border_style' => 'modern',
                'is_active'    => true,
                'created_by'   => $admin->id,
                'html_content' => $this->getAchievementTemplate(),
            ]
        );

        $this->command->info('✅ Seeding complete!');
        $this->command->info('Admin: admin@college.edu / Admin@1234');
        $this->command->info('HOD:   hod.cs@college.edu / Hod@1234');
        $this->command->info('Faculty: faculty@college.edu / Faculty@1234');
    }

    private function getDefaultTemplate(): string
    {
        return <<<'HTML'
<!DOCTYPE html><html><head><meta charset="UTF-8">
<style>
@import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Lato:wght@300;400&display=swap');
*{margin:0;padding:0;box-sizing:border-box}
body{width:297mm;height:210mm;font-family:'Lato',sans-serif;background:#fff}
.cert{width:100%;height:100%;border:14px solid #1a3a5c;outline:3px solid #c9a84c;outline-offset:-20px;padding:28px 50px;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;position:relative}
.college{font-size:12px;letter-spacing:4px;color:#1a3a5c;text-transform:uppercase;margin-bottom:4px}
.title{font-family:'Cinzel',serif;font-size:42px;color:#c9a84c;margin:8px 0}
.sub{font-size:11px;letter-spacing:5px;color:#888;text-transform:uppercase;margin-bottom:18px}
.to{font-size:12px;color:#999;margin-bottom:4px}
.name{font-family:'Cinzel',serif;font-size:30px;color:#1a3a5c;border-bottom:2px solid #c9a84c;padding-bottom:5px;margin:4px 0 12px}
.body{font-size:12px;color:#555;line-height:1.9;max-width:500px}
.body strong{color:#1a3a5c}
.footer{display:flex;justify-content:space-between;width:100%;margin-top:28px;align-items:flex-end}
.sig{text-align:center}.sig-line{border-top:1px solid #aaa;padding-top:4px;width:150px;font-size:10px;color:#333}
.cert-id{font-size:8px;color:#bbb;position:absolute;bottom:18px;left:50%;transform:translateX(-50%);white-space:nowrap}
.qr{position:absolute;bottom:22px;right:35px;width:55px;height:55px}
</style></head><body>
<div class="cert">
<p class="college">{{college_name}}</p>
<h1 class="title">Certificate</h1>
<p class="sub">of Participation</p>
<p class="to">This is proudly presented to</p>
<h2 class="name">{{student_name}}</h2>
<p class="body">
Enrollment No: <strong>{{enrollment_number}}</strong> &nbsp;&bull;&nbsp; {{student_branch}} &nbsp;&bull;&nbsp; {{student_year}}<br><br>
for successfully participating in the<br>
<strong>{{event_name}}</strong><br>
held on <strong>{{event_date}}</strong> at {{venue}}
</p>
<div class="footer">
<div class="sig"><div class="sig-line">{{issued_by}}<br><small>{{issuer_designation}}</small></div></div>
<div class="sig"><div class="sig-line">Date: {{issued_date}}</div></div>
</div>
<p class="cert-id">Cert ID: {{certificate_id}} &nbsp;|&nbsp; Block: {{block_hash}}</p>
<div class="qr">{{{qr_code}}}</div>
</div></body></html>
HTML;
    }

    private function getAchievementTemplate(): string
    {
        return <<<'HTML'
<!DOCTYPE html><html><head><meta charset="UTF-8">
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Open+Sans:wght@300;400&display=swap');
*{margin:0;padding:0;box-sizing:border-box}
body{width:297mm;height:210mm;font-family:'Open Sans',sans-serif;background:#fff}
.cert{width:100%;height:100%;background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);padding:30px 55px;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;position:relative;color:#fff}
.badge{width:60px;height:60px;border:3px solid #c9a84c;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:24px}
.college{font-size:11px;letter-spacing:5px;color:#c9a84c;text-transform:uppercase;margin-bottom:5px}
.title{font-family:'Playfair Display',serif;font-size:40px;color:#fff;margin:6px 0}
.sub{font-size:11px;letter-spacing:4px;color:#adc8e0;text-transform:uppercase;margin-bottom:16px}
.to{font-size:11px;color:#aaa;margin-bottom:3px}
.name{font-family:'Playfair Display',serif;font-size:28px;color:#c9a84c;margin:3px 0 10px}
.body{font-size:11.5px;color:#d0dde5;line-height:1.9;max-width:490px}
.body strong{color:#fff}
.footer{display:flex;justify-content:space-between;width:100%;margin-top:26px;align-items:flex-end}
.sig{text-align:center}.sig-line{border-top:1px solid rgba(255,255,255,.3);padding-top:4px;width:150px;font-size:10px;color:#ccc}
.cert-id{font-size:8px;color:rgba(255,255,255,.4);position:absolute;bottom:16px;left:50%;transform:translateX(-50%);white-space:nowrap}
.qr{position:absolute;bottom:20px;right:35px;width:55px;height:55px;filter:invert(1)}
</style></head><body>
<div class="cert">
<div class="badge">🏆</div>
<p class="college">{{college_name}}</p>
<h1 class="title">Certificate of Achievement</h1>
<p class="sub">Excellence &bull; Innovation &bull; Leadership</p>
<p class="to">Proudly awarded to</p>
<h2 class="name">{{student_name}}</h2>
<p class="body">
<strong>{{achievement}}</strong><br>
Enrollment: {{enrollment_number}} &nbsp;&bull;&nbsp; {{student_branch}} &nbsp;&bull;&nbsp; {{student_year}}<br><br>
{{description}}<br>at <strong>{{event_name}}</strong> &mdash; {{event_date}}
</p>
<div class="footer">
<div class="sig"><div class="sig-line">{{issued_by}}<br><small>{{issuer_designation}}</small></div></div>
<div class="sig"><div class="sig-line">{{issued_date}}</div></div>
</div>
<p class="cert-id">Cert ID: {{certificate_id}} &nbsp;|&nbsp; Block: {{block_hash}}</p>
<div class="qr">{{{qr_code}}}</div>
</div></body></html>
HTML;
    }
}
