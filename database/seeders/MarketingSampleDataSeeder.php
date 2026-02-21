<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MarketingSampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Insert Marketing Lists
        $listIds = DB::table('marketing_lists')->insertGetId([
            'name' => 'Summer Campaign 2024',
            'description' => 'Leads from summer enrollment campaign',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $list2Id = DB::table('marketing_lists')->insertGetId([
            'name' => 'Winter Batch 2024',
            'description' => 'Leads for winter batch admissions',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Insert Marketing Campaigns
        $campaign1Id = DB::table('marketing_campaigns')->insertGetId([
            'name' => 'Summer Enrollment Drive',
            'description' => 'Campaign to enroll students for summer batch',
            'type' => 'email',
            'status' => 'active',
            'audience' => 'all',
            'subject' => 'all',
            'grade' => 'all',
            'skip' => 'all',
            'list_id' => $listIds,
            'aisensy_campaign_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $campaign2Id = DB::table('marketing_campaigns')->insertGetId([
            'name' => 'WhatsApp Outreach',
            'description' => 'WhatsApp campaign for new admissions',
            'type' => 'whatsapp',
            'status' => 'active',
            'audience' => 'active',
            'subject' => 'Mathematics',
            'grade' => '10',
            'skip' => 'no',
            'list_id' => $list2Id,
            'aisensy_campaign_id' => 'WA123456',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $campaign3Id = DB::table('marketing_campaigns')->insertGetId([
            'name' => 'SMS Blast January',
            'description' => 'SMS campaign for course promotion',
            'type' => 'sms',
            'status' => 'inactive',
            'audience' => 'all',
            'subject' => 'all',
            'grade' => 'all',
            'skip' => 'all',
            'list_id' => null,
            'aisensy_campaign_id' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Insert Marketing Leads
        $leads = [
            [
                'name' => 'Rahul Sharma',
                'phone' => '9876543210',
                'email' => 'rahul.sharma@email.com',
                'source' => 'Website',
                'status' => 'active',
                'grade' => '10',
                'subject' => 'Mathematics',
                'skip' => 'no',
                'notes' => 'Interested in math coaching',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Priya Patel',
                'phone' => '9876543211',
                'email' => 'priya.patel@email.com',
                'source' => 'Facebook',
                'status' => 'active',
                'grade' => '12',
                'subject' => 'Physics',
                'skip' => 'no',
                'notes' => 'Looking for IIT coaching',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Amit Kumar',
                'phone' => '9876543212',
                'email' => 'amit.kumar@email.com',
                'source' => 'Google Ads',
                'status' => 'inactive',
                'grade' => '9',
                'subject' => 'Science',
                'skip' => 'yes',
                'notes' => 'Not responding to calls',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Sneha Gupta',
                'phone' => '9876543213',
                'email' => 'sneha.gupta@email.com',
                'source' => 'Referral',
                'status' => 'converted',
                'grade' => '11',
                'subject' => 'Chemistry',
                'skip' => 'no',
                'notes' => 'Enrolled in chemistry batch',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Vikram Singh',
                'phone' => '9876543214',
                'email' => 'vikram.singh@email.com',
                'source' => 'Website',
                'status' => 'active',
                'grade' => '10',
                'subject' => 'Mathematics',
                'skip' => 'no',
                'notes' => 'Demo class scheduled',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Anjali Desai',
                'phone' => '9876543215',
                'email' => 'anjali.desai@email.com',
                'source' => 'Instagram',
                'status' => 'active',
                'grade' => '8',
                'subject' => 'English',
                'skip' => 'no',
                'notes' => 'Interested in language courses',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Rajesh Verma',
                'phone' => '9876543216',
                'email' => 'rajesh.verma@email.com',
                'source' => 'Facebook',
                'status' => 'inactive',
                'grade' => '12',
                'subject' => 'Biology',
                'skip' => 'yes',
                'notes' => 'Number not reachable',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Meera Reddy',
                'phone' => '9876543217',
                'email' => 'meera.reddy@email.com',
                'source' => 'Google Ads',
                'status' => 'converted',
                'grade' => '10',
                'subject' => 'Mathematics',
                'skip' => 'no',
                'notes' => 'Paid full fees',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Suresh Nair',
                'phone' => '9876543218',
                'email' => 'suresh.nair@email.com',
                'source' => 'Referral',
                'status' => 'active',
                'grade' => '9',
                'subject' => 'Science',
                'skip' => 'no',
                'notes' => 'Parent meeting scheduled',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Kavita Joshi',
                'phone' => '9876543219',
                'email' => 'kavita.joshi@email.com',
                'source' => 'Website',
                'status' => 'active',
                'grade' => '11',
                'subject' => 'Physics',
                'skip' => 'no',
                'notes' => 'Wants weekend batch',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $leadIds = [];
        foreach ($leads as $lead) {
            $leadIds[] = DB::table('marketing_leads')->insertGetId($lead);
        }

        // Assign leads to campaigns (marketing_campaign_lead pivot)
        DB::table('marketing_campaign_lead')->insert([
            ['campaign_id' => $campaign1Id, 'lead_id' => $leadIds[0], 'created_at' => $now, 'updated_at' => $now],
            ['campaign_id' => $campaign1Id, 'lead_id' => $leadIds[1], 'created_at' => $now, 'updated_at' => $now],
            ['campaign_id' => $campaign1Id, 'lead_id' => $leadIds[4], 'created_at' => $now, 'updated_at' => $now],
            ['campaign_id' => $campaign2Id, 'lead_id' => $leadIds[2], 'created_at' => $now, 'updated_at' => $now],
            ['campaign_id' => $campaign2Id, 'lead_id' => $leadIds[3], 'created_at' => $now, 'updated_at' => $now],
            ['campaign_id' => $campaign2Id, 'lead_id' => $leadIds[5], 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Assign leads to lists (marketing_list_lead pivot)
        DB::table('marketing_list_lead')->insert([
            ['list_id' => $listIds, 'lead_id' => $leadIds[0], 'created_at' => $now, 'updated_at' => $now],
            ['list_id' => $listIds, 'lead_id' => $leadIds[1], 'created_at' => $now, 'updated_at' => $now],
            ['list_id' => $listIds, 'lead_id' => $leadIds[2], 'created_at' => $now, 'updated_at' => $now],
            ['list_id' => $listIds, 'lead_id' => $leadIds[3], 'created_at' => $now, 'updated_at' => $now],
            ['list_id' => $list2Id, 'lead_id' => $leadIds[4], 'created_at' => $now, 'updated_at' => $now],
            ['list_id' => $list2Id, 'lead_id' => $leadIds[5], 'created_at' => $now, 'updated_at' => $now],
            ['list_id' => $list2Id, 'lead_id' => $leadIds[6], 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->command->info('Marketing sample data seeded successfully!');
        $this->command->info('- 3 Campaigns created');
        $this->command->info('- 10 Leads created');
        $this->command->info('- 2 Lists created');
    }
}
