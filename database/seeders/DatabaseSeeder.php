<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // $this->call(::class);
        $this->call(CompanySeeder::class);
        $this->call(BranchSeeder::class);
        $this->call([CategorySeeder::class]);
        $this->call(DepartmentSeeder::class);
        $this->call(DesignationSeeder::class);

        $this->call(PermissionTableSeeder::class);
        $this->call(BanksSeeder::class);
        $this->call(BankBranchesSeeder::class);
        $this->call(BankAccountsSeeder::class);
        $this->call(AccountTypesSeeder::class);
        // $this->call(LedgersSeeder::class);
        $this->call(WorkShiftsTableSeeder::class);
        $this->call(StudentSeeder::class);

        
        // $this->call(CreateCourseTypeSeeder::class);
        // $this->call(CreateCourseSeeder::class);
        // $this->call(CountrySeeder::class);
        // $this->call(StateSeeder::class);
        // $this->call(CitySeeder::class);
        // $this->call(GroupSeeder::class);
        $this->call(CurrencySeeder::class);
        // $this->call(SettingsSeed::class);
        // $this->call(BudgetsSeeder::class);
        //$this->call(SessionSeeder::class);
        $this->call(CreateAdminUserSeeder::class);
        $this->call(EmployeeSeeder::class);
        $this->call(StudentSeeder::class);  
        // $this->call(AgentSeeder::class);
        // $this->call(AgentRole::class);
        // $this->call(TeacherRole::class);
        // $this->call(OfficeBoyRole::class);
        // $this->call(StudentRole::class);
        //  $this->call(HRUserSeeder::class);
        //  $this->call(HODUserSeeder::class);
        //  $this->call(TeamLeadUserSeeder::class);
        // $this->call(EntryTypeSeeder::class);
        // $this->call(EmployeeTypeSeeder::class);
        // $this->call(EmployeeSeeder::class);
        // $this->call(VideoSeeder::class);
        // $this->call(VideoCategorySeeder::class);
        // $this->call(AgentTypeSeeder::class);
        // $this->call(SlabsSeeder::class);
        // $this->call([GeneralSettingSeeder::class]);
        // $this->call([GroupsTableSeeder::class]);
        // $this->call([InventoryCategorySeeder::class]);
        // $this->call([VendorCategory::class]);
        // $this->call([LoopData::class]);


    }
}
