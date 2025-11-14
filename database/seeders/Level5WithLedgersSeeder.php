<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Accounts\AccountGroup;
use App\Models\Accounts\AccountLedger;

class Level5WithLedgersSeeder extends Seeder
{

    public function run()
    {
        // wrap in transaction so partial failures rollback
        DB::beginTransaction();

        try {
            // Default ledger attributes (adjust if you want different defaults)
            $ledgerDefaults = [
                'opening_balance' => 0,
                'opening_balance_type' => 'debit',
                'current_balance' => 0,
                'current_balance_type' => 'debit',
                'currency_id' => 1,
                'is_active' => true,
                'is_system' => false,
                'linked_module' => null,
                'linked_id' => null,
                'branch_id' => null,
                'created_by' => null,
                'updated_by' => null,
            ];

            // === PENDING LEVEL-5 DATA (exact as provided) ===
            $pending = [

                // Under 010010010001 -> Fixed Assets
                '010010010001' => [
                    ['code' => '00001', 'name' => 'Land'],
                    ['code' => '00002', 'name' => 'Building on Leasehold Land'],
                    ['code' => '00003', 'name' => 'Furniture & Fixture'],
                    ['code' => '00004', 'name' => 'Office Equipment'],
                    ['code' => '00005', 'name' => 'Computer Equipment'],
                    ['code' => '00006', 'name' => 'Electric Equipment'],
                    ['code' => '00007', 'name' => 'Laboratory Equipment'],
                    ['code' => '00008', 'name' => 'Course Books (3 years)'],
                    ['code' => '00009', 'name' => 'Other Assets'],
                    ['code' => '00010', 'name' => 'Vehicle'],
                    ['code' => '00011', 'name' => 'Security Equipments'],
                    ['code' => '00012', 'name' => 'Course books (5 years)'],
                    ['code' => '00013', 'name' => 'Library Books'],
                    ['code' => '00014', 'name' => 'Plant & Machinery'],
                    ['code' => '00015', 'name' => 'Electric Installations'],
                    ['code' => '00016', 'name' => 'Building Improvements'],
                    ['code' => '00017', 'name' => 'Software & Systems'],
                    ['code' => '00018', 'name' => 'kitchen Equipment'],
                ],

                // Under 010010010002 -> Accumulated Depreciation
                '010010010002' => [
                    ['code' => '00001', 'name' => 'Acc Dep- Building on Leasehold Land'],
                    ['code' => '00002', 'name' => 'Acc Dep - Furniture'],
                    ['code' => '00003', 'name' => 'Acc Dep - Office Equip'],
                    ['code' => '00004', 'name' => 'Acc Dep - Computer Equipment'],
                    ['code' => '00005', 'name' => 'Acc Dep - Electric Equipment'],
                    ['code' => '00006', 'name' => 'Acc Dep - Lab Equipment'],
                    ['code' => '00007', 'name' => 'Acc Dep - Library Books'],
                    ['code' => '00008', 'name' => 'Acc Dep - Other Assets'],
                    ['code' => '00009', 'name' => 'Acc Dep - Vehicle'],
                    ['code' => '00010', 'name' => 'Acc Dep - Security Equipments'],
                    ['code' => '00011', 'name' => 'Acc Amortization - Course Books (3 years)'],
                    ['code' => '00012', 'name' => 'Acc Dep - Electric installations'],
                    ['code' => '00013', 'name' => 'Acc Amortization - Course Books (5 years)'],
                    ['code' => '00014', 'name' => 'Acc Depreciation Plant & Machinery'],
                    ['code' => '00015', 'name' => 'Acc Dep - Kitchen Equipment'],
                    ['code' => '00016', 'name' => 'Acc Dep - Building Improvments'],
                ],

                // Under 040020010001 -> Sundry Creditors (full list)
                '040020010001' => [
                    ['code' => '00002','name'=>'Generations Systems'],
                    ['code' => '00003','name'=>'Mega Plus Pakistan'],
                    ['code' => '00004','name'=>'International Business System'],
                    ['code' => '00005','name'=>'Computer Care'],
                    ['code' => '00006','name'=>'Decent Computers'],
                    ['code' => '00007','name'=>'Star Uniform Books & Stationers'],
                    ['code' => '00008','name'=>'Ahmed Stationers'],
                    ['code' => '00009','name'=>'Rose Traders'],
                    ['code' => '00011','name'=>'Awan Brothers'],
                    ['code' => '00025','name'=>'Wintech International'],
                    ['code' => '00026','name'=>'Amjad Saeed'],
                    ['code' => '00027','name'=>'AS Advertising Source'],
                    ['code' => '00028','name'=>'Darusalam Islamic Publishers'],
                    ['code' => '00029','name'=>'Future Visions Advertising'],
                    ['code' => '00030','name'=>'Ghani Value Glass LTD'],
                    ['code' => '00031','name'=>'Good Wood'],
                    ['code' => '00032','name'=>'Hafiz Steel'],
                    ['code' => '00033','name'=>'HY Design House'],
                    ['code' => '00034','name'=>'HY Enterprises'],
                    ['code' => '00035','name'=>'Ishfaq Ahmad'],
                    ['code' => '00036','name'=>'King Fashion'],
                    ['code' => '00037','name'=>'Kundi Services (Pvt) Ltd'],
                    ['code' => '00038','name'=>'MMA Steel Rack'],
                    ['code' => '00039','name'=>'Munawar Habib'],
                    ['code' => '00040','name'=>'Paramount Books (PVT) Ltd'],
                    ['code' => '00041','name'=>'Prime Vision'],
                    ['code' => '00042','name'=>'Publishers Marketing Associates'],
                    ['code' => '00043','name'=>'Saleem Sons'],
                    ['code' => '00044','name'=>'Sedar Interiors'],
                    ['code' => '00045','name'=>'Shan Flowers Decorators'],
                    ['code' => '00046','name'=>'Master Interiors'],
                    ['code' => '00047','name'=>'Modern Wires & cables Industries Pvt Ltd'],
                    ['code' => '00048','name'=>'Mr Shahzad Sarwar'],
                    ['code' => '00049','name'=>'Publisher Marketing Associates'],
                    ['code' => '00050','name'=>'Pak Photocopy Center'],
                    ['code' => '00051','name'=>'The Pioneers'],
                    ['code' => '00052','name'=>'Ruba digital Private Limited'],
                    ['code' => '00053','name'=>'UFW Communication Services'],
                    ['code' => '00054','name'=>'win outdoor furniture'],
                    ['code' => '00055','name'=>'Global Links'],
                    ['code' => '00056','name'=>'Radiance International'],
                    ['code' => '00057','name'=>'Decora'],
                    ['code' => '00058','name'=>'Native Steel Corporation'],
                    ['code' => '00059','name'=>'All Fazal Enterprises'],
                    ['code' => '00060','name'=>'Guangzhou Kosa Furniture Co, Ltd'],
                    ['code' => '00061','name'=>'Care Communication'],
                    ['code' => '00062','name'=>'Muhammad Tariq'],
                    ['code' => '00063','name'=>'Time and Xcess Information Systems (pvt) LTD'],
                    ['code' => '00064','name'=>'S.A Electric Co'],
                    ['code' => '00065','name'=>'Vanguard Book (Pvy) Ltd'],
                    ['code' => '00066','name'=>'Readings'],
                    ['code' => '00067','name'=>'Turbo House Hold'],
                    ['code' => '00068','name'=>'Carpet Inn'],
                    ['code' => '00069','name'=>'Humak Engineering Pvt LTD'],
                    ['code' => '00070','name'=>'Green Appliances Pvt LTD'],
                    ['code' => '00071','name'=>'Live Tech'],
                    ['code' => '00072','name'=>'Digital Plus'],
                    ['code' => '00073','name'=>'Electro City'],
                    ['code' => '00074','name'=>'Zulfiqar Security Company (Pvt) LTD'],
                    ['code' => '00075','name'=>'DADA Engineering Company'],
                    ['code' => '00076','name'=>'Noble Grow'],
                    ['code' => '00077','name'=>'Ashiana Interior'],
                    ['code' => '00078','name'=>'Ejaz Shahid'],
                    ['code' => '00079','name'=>'Energy Solution'],
                    ['code' => '00080','name'=>'Ali Hassan'],
                    ['code' => '00081','name'=>'Linkers Office Machines & System'],
                    ['code' => '00082','name'=>'Niaz Ahmed'],
                    ['code' => '00083','name'=>'Ahmed Collection'],
                    ['code' => '00084','name'=>'Shahbaz Ali'],
                    ['code' => '00085','name'=>'Telenor'],
                    ['code' => '00086','name'=>'Al-Fateh'],
                    ['code' => '00087','name'=>'Ishaq Sons'],
                    ['code' => '00088','name'=>'Ayaz Mahmood'],
                    ['code' => '00089','name'=>'Brandsol'],
                    ['code' => '00090','name'=>'Fidem Education Network'],
                    ['code' => '00091','name'=>'IP BIZ'],
                    ['code' => '00092','name'=>'Rashid Ahmed'],
                    ['code' => '00093','name'=>'P.T.C.H.S'],
                    ['code' => '00094','name'=>'2A-Lifemed'],
                    ['code' => '00095','name'=>'RH Designs'],
                    ['code' => '00096','name'=>'Ali Mahmood (Ambassador)'],
                    ['code' => '00097','name'=>'NetSoft Solutions'],
                    ['code' => '00098','name'=>'UOL Cafeteria'],
                    ['code' => '00099','name'=>'Medap International'],
                    ['code' => '00100','name'=>'Pak Surgical'],
                    ['code' => '00101','name'=>'The Equippers'],
                    ['code' => '00102','name'=>'Equip International'],
                    ['code' => '00103','name'=>'Tariq Mechanical Works'],
                    ['code' => '00104','name'=>'Ramzaan Steel'],
                    ['code' => '00105','name'=>'Haider Ajaib'],
                    ['code' => '00106','name'=>'Usman  leather store'],
                    ['code' => '00107','name'=>'International Business Systems'],
                    ['code' => '00108','name'=>'Afzal Printers'],
                    ['code' => '00109','name'=>'Muhammad Abdul Qayyum'],
                    ['code' => '00110','name'=>'Sakhawat Ali'],
                    ['code' => '00111','name'=>'Ghousia Light House'],
                    ['code' => '00112','name'=>'infinity construction company'],
                    ['code' => '00113','name'=>'Muhammad Zain'],
                    ['code' => '00114','name'=>'FUtech Productions'],
                    ['code' => '00115','name'=>'Sufi Shab'],
                    ['code' => '00116','name'=>'Waheed ur Rehman'],
                    ['code' => '00117','name'=>'LESCO (WAPDA)'],
                    ['code' => '00118','name'=>'Muhammad Imran'],
                    ['code' => '00119','name'=>'Tahir Hussain'],
                    ['code' => '00120','name'=>'Mr Shafaqat'],
                    ['code' => '00121','name'=>'Muhammad Arshad'],
                    ['code' => '00122','name'=>'Waseem Glass'],
                    ['code' => '00123','name'=>'Fine Wood'],
                    ['code' => '00124','name'=>'SW Electronics'],
                    ['code' => '00125','name'=>'Multi Electronics'],
                    ['code' => '00126','name'=>'Pak Anti Fire'],
                    ['code' => '00127','name'=>'M Elahi Traders'],
                    ['code' => '00128','name'=>'Siraj News Agency'],
                    ['code' => '00129','name'=>'Mr Khalid'],
                    ['code' => '00130','name'=>'Khan Oriental Carpet & Rugs'],
                    ['code' => '00131','name'=>'Ramzan Steel'],
                    ['code' => '00132','name'=>'M.S. Corporation'],
                    ['code' => '00133','name'=>'AR Advertiser'],
                    ['code' => '00134','name'=>'Elan Vital Pvt Ltd'],
                    ['code' => '00135','name'=>'Book Lovers'],
                    ['code' => '00136','name'=>'amjad sindhu'],
                    ['code' => '00137','name'=>'PESSI'],
                    ['code' => '00138','name'=>'Color aluminium & glass house'],
                    ['code' => '00139','name'=>'Clicker Vision'],
                    ['code' => '00140','name'=>'Sadiq Publications'],
                    ['code' => '00141','name'=>'Sheema Aizaz Cheema'],
                    ['code' => '00143','name'=>'Network Empire'],
                    ['code' => '00144','name'=>'Siddiqui Book Company'],
                    ['code' => '00145','name'=>'M.A.Polymers'],
                    ['code' => '00146','name'=>'Print Copy Services'],
                    ['code' => '00147','name'=>'Rehman Electric'],
                    ['code' => '00148','name'=>'AVL Engerprises (SMC-PVT) LTD'],
                    ['code' => '00149','name'=>'Citi Science Traders'],
                    ['code' => '00150','name'=>'M.Elaahi Traders'],
                    ['code' => '00151','name'=>'Vortex Past Papers'],
                    ['code' => '00152','name'=>'Allied Book Company'],
                    ['code' => '00153','name'=>'Future Technologies'],
                    ['code' => '00154','name'=>'United Traders'],
                    ['code' => '00155','name'=>'Universal Cables Industries Ltd'],
                    ['code' => '00156','name'=>'Align Construction'],
                    ['code' => '00157','name'=>'STAIRS'],
                    ['code' => '00158','name'=>'Quality Printers'],
                    ['code' => '00159','name'=>'Multiline Fire Protection'],
                    ['code' => '00160','name'=>'Pakistan Technocrats Cooperative Housing Society'],
                    ['code' => '00161','name'=>'New Image Solution'],
                    ['code' => '00162','name'=>'Sher Shah Books & Photocopy'],
                    ['code' => '00163','name'=>'ALIGARH ENTERPRISES'],
                    ['code' => '00164','name'=>'UOL Fuel station'],
                    ['code' => '00165','name'=>'S.M Jaffer & Co'],
                    ['code' => '00166','name'=>'MRF Trading'],
                    ['code' => '00167','name'=>'UOL Pharmacy'],
                    ['code' => '00168','name'=>'Hafiz Computers'],
                    ['code' => '00169','name'=>'So Safe'],
                    ['code' => '00170','name'=>'Crown Enterprises'],
                    ['code' => '00171','name'=>'BOOK EXPRESS'],
                    ['code' => '00172','name'=>'Fine Interior'],
                    ['code' => '00173','name'=>'International Garments'],
                    ['code' => '00174','name'=>'Cash Purchases'],
                    ['code' => '00175','name'=>'Paradise Photocopy Center (Mukhtar Ahmad)'],
                    ['code' => '00176','name'=>'UOL Teaching Hospital'],
                    ['code' => '00177','name'=>'Dream On'],
                    ['code' => '00178','name'=>'RAZZAQ ELECTRIC STORE'],
                    ['code' => '00179','name'=>'Javed Iqbal (Security Guards Food Hotel)'],
                    ['code' => '00180','name'=>'IT SHOP'],
                    ['code' => '00181','name'=>'M M ENTERPRISES'],
                    ['code' => '00182','name'=>'Ghosia Book Centre'],
                    ['code' => '00183','name'=>'Professional Designers (pd)'],
                    ['code' => '00184','name'=>'WATER WAVES'],
                    ['code' => '00185','name'=>'BEACON LIGHTING SOLUTIONS'],
                    ['code' => '00186','name'=>'M-TECH MULTI TECHNOLOGY PVT LTD'],
                    ['code' => '00187','name'=>'Rana Printing Press'],
                    ['code' => '00188','name'=>'INTKHAB SPORTS'],
                    ['code' => '00189','name'=>'MOHSIN TRADERS'],
                    ['code' => '00190','name'=>'DASTGIR ENGINEERING'],
                    ['code' => '00191','name'=>'Get Technologies pvt Ltd'],
                    ['code' => '00192','name'=>'ZEESHAN PRINTERS & STATIONERS'],
                    ['code' => '00193','name'=>'S A ENTERPRISES'],
                    ['code' => '00194','name'=>'Dost Land Printers'],
                    ['code' => '00195','name'=>'Naheed Rubab'],
                    ['code' => '00196','name'=>'Azeem Architects'],
                    ['code' => '00197','name'=>'Book Land'],
                    ['code' => '00198','name'=>'One Time Vendor'],
                    ['code' => '00199','name'=>'QURESHI ELECTRIC STORE'],
                    ['code' => '00200','name'=>'PERFECT METAL CRAFT'],
                    ['code' => '00201','name'=>'UOL Vendor Account'],
                    ['code' => '00202','name'=>'Muhammad Abdullah Khan'],
                    ['code' => '00203','name'=>'NOOR AL KHAIR TECHNOLOGIES'],
                    ['code' => '00204','name'=>'Ganj Bakhsh Bags'],
                    ['code' => '00205','name'=>'ALI PRINTERS & ADVERTISING'],
                    ['code' => '00206','name'=>'IMPORIENT CHEMICALS (PRIVATE) LIMITED'],
                    ['code' => '00207','name'=>'TCS (PRIVATE) LIMITED'],
                    ['code' => '00208','name'=>'MR TRADERS'],
                    ['code' => '00209','name'=>'MTS MEDIA SOLUTIONS'],
                    ['code' => '00210','name'=>'Haris Aluminium'],
                    ['code' => '00211','name'=>'M/S OUTSTART TECH'],
                    ['code' => '00212','name'=>'RANA INTERIORS'],
                    ['code' => '00213','name'=>'PROFESSIONAL DOCUMENT SOLUTIONS'],
                    ['code' => '00214','name'=>'Solution'],
                    ['code' => '00215','name'=>'PROFINE FURNITURE INDUSTRIES (PVT.) LIMITED'],
                    ['code' => '00216','name'=>'B BROTHERS COMPUTERS'],
                    ['code' => '00217','name'=>'NSA ENGINEERING'],
                    ['code' => '00218','name'=>'SHA FIRE PROTECTION'],
                    ['code' => '00219','name'=>'STANDARD BRANDS PRIVATE LIMITED'],
                    ['code' => '00220','name'=>'MUHAMMAD HANIF'],
                    ['code' => '00221','name'=>'KITAAB BAZAR ONLINE'],
                    ['code' => '00222','name'=>'TREND WOOD'],
                    ['code' => '00223','name'=>'MS Network Solution'],
                    ['code' => '00224','name'=>'M.A.CONTAINER SERVICE'],
                    ['code' => '00225','name'=>'SHAKEEL ENTERPRISES'],
                    ['code' => '00226','name'=>'eWall Studio'],
                    ['code' => '00227','name'=>'MASCOT WALL INTERIOR'],
                    ['code' => '00228','name'=>'ROYAL FURNITURE HOUSE'],
                    ['code' => '00229','name'=>'Tradex CO'],
                    ['code' => '00230','name'=>'ICE-AGE Industries Pvt Ltd.'],
                    ['code' => '00231','name'=>'BASHIR DESIGN FURNITURE'],
                    ['code' => '00232','name'=>'SARINA FLOORINGS'],
                    ['code' => '00233','name'=>'Orient Energy Systems Private Limited'],
                    ['code' => '00234','name'=>'Tariq Naveed'],
                    ['code' => '00235','name'=>'Bashir & Sons'],
                    ['code' => '00236','name'=>'ABDUL REHMAN'],
                    ['code' => '00237','name'=>'PRINCE MUSIC CENTRE'],
                    ['code' => '00238','name'=>'PAK CARPET & INTERIORS (PRIVATE) LIMITED'],
                    ['code' => '00239','name'=>'KHASHB FURNISHER'],
                    ['code' => '00240','name'=>'Digital Traders'],
                    ['code' => '00242','name'=>'General Fan Company Limited'],
                    ['code' => '00243','name'=>'Al Najjar Communications'],
                    ['code' => '00244','name'=>'Nasak Traders'],
                    ['code' => '00245','name'=>'GM Cables & Pipes PVT LTD'],
                    ['code' => '00246','name'=>'Syed Sons'],
                    ['code' => '00247','name'=>'Usman Electric Centre'],
                    ['code' => '00248','name'=>'Scientific Valley'],
                    ['code' => '00249','name'=>'Scaryammi'],
                    ['code' => '00250','name'=>'Smart Furnishers'],
                    ['code' => '00251','name'=>'Ramna Food Products (PVT) Ltd'],
                    ['code' => '00252','name'=>'Sajid Flour Mills (PVT) Ltd'],
                    ['code' => '00253','name'=>'JK Traders'],
                    ['code' => '00254','name'=>'HUSNAIN TRADERS'],
                    ['code' => '00255','name'=>'HASSNAIN TRADERS'],
                    ['code' => '00256','name'=>'AFGHAN TRADING COMPANY'],
                    ['code' => '00257','name'=>'EMAAN ENTERPRISES'],
                    ['code' => '00258','name'=>'Ahmad Traders'],
                    ['code' => '00259','name'=>'Waqas Haider'],
                    ['code' => '00260','name'=>'MPI PRIVATE LIMITED'],
                    ['code' => '00261','name'=>'Masood Mirza Ali Khan Burki (Lot No 42)'],
                    ['code' => '00262','name'=>'Muhammad Latif Nadeem (Lot No 43)'],
                    ['code' => '00263','name'=>'Mangla Garrison Housing (Lot No 48)'],
                    ['code' => '00264','name'=>'Masood Mirza Ali Khan Burki (Lot No 50)'],
                    ['code' => '00265','name'=>'Craft On'],
                    ['code' => '00266','name'=>'Secon Engineering Services'],
                    ['code' => '00267','name'=>'CSS Main Campus'],
                    ['code' => '00268','name'=>'Interwood'],
                    ['code' => '00269','name'=>'Bismillah Chicken Supplier'],
                    ['code' => '00270','name'=>'Craft On ( Muhammad Hamzaa Mazhar )'],
                    ['code' => '00271','name'=>'Wahdat Poultry Farm ( PVT ) Ltd'],
                    ['code' => '00272','name'=>'Muhammad Aslam Milk Shop'],
                    ['code' => '00273','name'=>'M.Habib Mughal'],
                    ['code' => '00274','name'=>'A.R TRADERS'],
                    ['code' => '00275','name'=>'Khawer Gas Center'],
                    ['code' => '00276','name'=>'Muhammad Aslam (MILK SHOP)'],
                    ['code' => '00277','name'=>'Haier Pakistan (PVT) Ltd'],
                    ['code' => '00278','name'=>'Hyper Trading'],
                    ['code' => '00279','name'=>'Fatima Engineers'],
                    ['code' => '00280','name'=>'SAAD AZAM  (Inside Decors )'],
                    ['code' => '00281','name'=>'Brainwave Media Communication'],
                    ['code' => '00282','name'=>'Westag International'],
                    ['code' => '00283','name'=>'The Paracha Textile Mills Limited'],
                    ['code' => '00284','name'=>'Ameer Akbar ( 8 BC Commercial Area )'],
                    ['code' => '00285','name'=>'Super Cool Point'],
                    ['code' => '00286','name'=>'Muhammad Naveed Akbar Khan'],
                    ['code' => '00287','name'=>'WESTCON CONSTRUCTIONS (PVT) LIMITED'],
                    ['code' => '00288','name'=>'Ameer Akbar'],
                    ['code' => '00289','name'=>'R.A.Engineering & Services (PVT) Ltd'],
                    ['code' => '00290','name'=>'Robotronics Pakistan (Private) Limited'],
                    ['code' => '00291','name'=>'THE POET (SMC-PRIVATE) LTD'],
                    ['code' => '00292','name'=>'92 Publishing House'],
                    ['code' => '00293','name'=>'WASA LAHORE'],
                    ['code' => '00294','name'=>'KiFAYAT PUBLISHERS'],
                    ['code' => '00295','name'=>'RJ CHEMICALS'],
                    ['code' => '00296','name'=>'Urdu Bazar Mall (Stationary)'],
                    ['code' => '00297','name'=>'Inaham Sports'],
                    ['code' => '00298','name'=>'Mustafa Computer Stationary'],
                    ['code' => '00299','name'=>'Taha Enterprises'],
                    ['code' => '00300','name'=>'International Safety Solution'],
                    ['code' => '00301','name'=>'Mohsin Noor (Farm House)'],
                    ['code' => '00302','name'=>'UOL Relief Trust'],
                    ['code' => '00303','name'=>'Husnain Ghani Traders'],
                    ['code' => '00304','name'=>'Ali traders (Bahader & Sons)'],
                    ['code' => '00305','name'=>'Overseas Enterprises'],
                    ['code' => '00306','name'=>'IT Tech'],
                    ['code' => '00307','name'=>'TRAVERSE'],
                    ['code' => '00308','name'=>'Ghazi (LPG) Gas'],
                    ['code' => '00309','name'=>'AL-HILAL CHICKEN SUPPLIER'],
                    ['code' => '00310','name'=>'Umer Trader'],
                    ['code' => '00311','name'=>'GREEN COMMUNICATION'],
                    ['code' => '00312','name'=>'Annualreports Pakistan'],
                    ['code' => '00313','name'=>'ARBAB STATIONERS'],
                    ['code' => '00314','name'=>'Hamza Stationery. Co'],
                    ['code' => '00315','name'=>'PARADISE PRINTERS'],
                    ['code' => '00316','name'=>'HAMMAD PAINT HOUSE'],
                    ['code' => '00317','name'=>'GROCER\'S PANTRY'],
                    ['code' => '00318','name'=>'Hydroprime Chemicals'],
                    ['code' => '00319','name'=>'SPECTRUM EDUCATIONAL RESEARCH FOUNDATION'],
                    ['code' => '00320','name'=>'Manzar Abbas Malik'],
                    ['code' => '00321','name'=>'Zain Tradars'],
                    ['code' => '00322','name'=>'MERAJ INTERNATIONAL'],
                    ['code' => '00323','name'=>'Punjab Sports'],
                    // If you had more Sundry Creditors beyond this line in your original list,
                    // paste the remaining items here maintaining the sequence & exact names.
                ],

                // Under 040020010002 -> Creditors For Services
                '040020010002' => [
                    ['code' => '00001','name'=>'M/S Hashaam Security Services (pvt) Ltd'],
                    ['code' => '00002','name'=>'JW Enviro Pakistan Pvt Ltd'],
                    ['code' => '00003','name'=>'SoftConsults'],
                    ['code' => '00004','name'=>'Future Visions'],
                    ['code' => '00005','name'=>'Robokids (Pvt) LTD'],
                    ['code' => '00006','name'=>'GloboBoss Events'],
                    ['code' => '00007','name'=>'PTCL'],
                    ['code' => '00008','name'=>'Teacch Training'],
                    ['code' => '00009','name'=>'Oaiss Trust'],
                    ['code' => '00010','name'=>'M/S AR Advertiser'],
                    ['code' => '00011','name'=>'Teach Education HRD'],
                    ['code' => '00012','name'=>'Shahzad Sarwar'],
                    ['code' => '00013','name'=>'Bahria Town (PVT) LTD Lahore'],
                    ['code' => '00014','name'=>'haider caterers'],
                    ['code' => '00015','name'=>'Split Care'],
                    ['code' => '00016','name'=>'Hassan Engineering'],
                    ['code' => '00017','name'=>'Slit Co.'],
                    ['code' => '00018','name'=>'PKNIC Pvt Limited'],
                    ['code' => '00019','name'=>'Ramzan Steel Art'],
                    ['code' => '00020','name'=>'Miss Shehnaz Rauf'],
                    ['code' => '00021','name'=>'Wateen Internet'],
                    ['code' => '00022','name'=>'AL Hassan Entertainment'],
                    ['code' => '00023','name'=>'Multinet'],
                    ['code' => '00024','name'=>'Khan Sound'],
                    ['code' => '00025','name'=>'Sahar Ilyas'],
                    ['code' => '00026','name'=>'Dream On'],
                    ['code' => '00027','name'=>'Al-Noor Contractors'],
                    ['code' => '00028','name'=>'Aqdus Aslam'],
                    ['code' => '00029','name'=>'Color Aluminum & Glass House'],
                    ['code' => '00030','name'=>'Mehwish Raza'],
                    ['code' => '00031','name'=>'Violet Ruiz Khan'],
                    ['code' => '00032','name'=>'Maritza Castillo Shahid'],
                    ['code' => '00033','name'=>'KHALIDA & KAUSAR (PRIVATE) LIMITED'],
                    ['code' => '00034','name'=>'Khadija Gohar'],
                    ['code' => '00035','name'=>'Deen\'s Group (Media & Marketing)'],
                    ['code' => '00036','name'=>'Gohar Rasheed Butt'],
                    ['code' => '00037','name'=>'SNGPL (Sui Northern Gas Pipelines Limited)'],
                    ['code' => '00038','name'=>'Black Gold'],
                    ['code' => '00039','name'=>'Danish Hameed Mirza (Steam)'],
                    ['code' => '00040','name'=>'Hamid Niaz (Tennis)'],
                    ['code' => '00041','name'=>'H & H ENTERPRISES'],
                    ['code' => '00042','name'=>'Zaffar Estate'],
                    ['code' => '00043','name'=>'Anam Ilyas'],
                    ['code' => '00044','name'=>'Tayyaba Umbreen'],
                    ['code' => '00045','name'=>'Abid Mehmood (UOL )'],
                    ['code' => '00046','name'=>'MUHAMMAD JAMIL KHAN'],
                    ['code' => '00047','name'=>'MBN Chemicals'],
                    ['code' => '00048','name'=>'Aqua Shine Technologies by Sane Enterprises'],
                    ['code' => '00049','name'=>'Westcon'],
                    ['code' => '00050','name'=>'Megabyte Soft Sollutions'],
                    ['code' => '00051','name'=>'N N Sign'],
                    ['code' => '00052','name'=>'B.M REAL ESTATE'],
                    ['code' => '00053','name'=>'URBAN PEST MANAGEMENT'],
                    ['code' => '00054','name'=>'OPTICS COMMUNICATIONS'],
                    ['code' => '00055','name'=>'Foxglove services'],
                    ['code' => '00056','name'=>'M/S SAGE TECH INTERNATIONAL'],
                    ['code' => '00057','name'=>'Marks-Men'],
                    ['code' => '00058','name'=>'Asim Advertising'],
                    ['code' => '00059','name'=>'Muhammad Aleem'],
                    ['code' => '00060','name'=>'Misfit'],
                    ['code' => '00061','name'=>'Sobia Imran (Flat No.8-B Commercial)'],
                    ['code' => '00062','name'=>'Muzaffar Ali'],
                    ['code' => '00063','name'=>'Aftab Ali'],
                    ['code' => '00064','name'=>'Adeeba Shafi'],
                    ['code' => '00065','name'=>'Abdul Wahab AD'],
                    ['code' => '00066','name'=>'Orthodox (Private) Limited'],
                    ['code' => '00067','name'=>'MUHAMMAD SHAFIQUE'],
                    ['code' => '00068','name'=>'Mangla Garrison Housing  Pvt Ltd ( MGH )'],
                    ['code' => '00069','name'=>'Muhammad Naseer Khokhar ( Shabir & CO )'],
                    ['code' => '00070','name'=>'Okay Construction Company'],
                    ['code' => '00071','name'=>'Usman Rice Traders'],
                    ['code' => '00072','name'=>'Zaheer Abbas (Shop)'],
                    ['code' => '00073','name'=>'A.A. ENTERPRISES'],
                    ['code' => '00074','name'=>'SS Studio'],
                    ['code' => '00075','name'=>'NISHAT HOTELS AND PROPERTIES LIMITED'],
                    ['code' => '00076','name'=>'NETROOTS TECHNOLOGIES'],
                    ['code' => '00077','name'=>'Spernet Limited'],
                    // If there were more items in your original list, paste them here exactly.
                ],
            ];

            // Ensure account_ledgers table exists
            if (! Schema::hasTable('account_ledgers')) {
                DB::rollBack();
                $this->command->error("Table 'account_ledgers' does not exist. Seed aborted.");
                return;
            }

            // iterate over parents and children, create groups + ledgers
            foreach ($pending as $parentCode => $children) {
                $parent = AccountGroup::where('code', $parentCode)->first();

                if (! $parent) {
                    $this->command->warn("Parent with code {$parentCode} not found. Skipping its children.");
                    continue;
                }

                foreach ($children as $item) {
                    // normalize fragment and pad to 5 digits
                    $frag = preg_replace('/\D/', '', $item['code']);
                    $frag = str_pad($frag, 5, '0', STR_PAD_LEFT);

                    // build full group code
                    $fullGroupCode = $parentCode . $frag;

                    // sanitize name
                    $name = trim(preg_replace('/^\s*\d+[\-\.\s]*/', '', $item['name']));

                    // create or get AccountGroup (idempotent)
                    $group = AccountGroup::firstOrCreate(
                        ['code' => $fullGroupCode],
                        [
                            'name' => $name,
                            'type' => $parent->type ?? null,
                            'level' => ($parent->level ? $parent->level + 1 : 5),
                            'description' => $item['name'] ?? null,
                            'parent_id' => $parent->id,
                            'is_active' => true,
                        ]
                    );

                    // Build ledger code (here we use group code as ledger code)
                    $ledgerCode = $fullGroupCode;

                    // Prepare ledger attributes matching your $fillable
                    $ledgerAttrs = array_merge($ledgerDefaults, [
                        'name' => $name,
                        'code' => $ledgerCode,
                        'description' => $item['name'] ?? null,
                        'account_group_id' => $group->id,
                    ]);

                    // If account_ledgers table has column 'code' we will search by it
                    $ledgerQuery = ['code' => $ledgerCode];
                    if (! Schema::hasColumn('account_ledgers', 'code')) {
                        // fallback to searching by name + account_group_id
                        $ledgerQuery = ['name' => $name, 'account_group_id' => $group->id];
                        unset($ledgerAttrs['code']);
                    }

                    // Create ledger idempotently
                    $ledger = AccountLedger::firstOrCreate($ledgerQuery, $ledgerAttrs);

                    // Ensure account_group_id is linked if ledger existed without it
                    if ($ledger->account_group_id != $group->id) {
                        $ledger->account_group_id = $group->id;
                        $ledger->save();
                    }

                    $this->command->info("Inserted/Found Group {$group->code} -> Ledger {$ledger->code} ({$ledger->name})");
                }
            }

            DB::commit();
            $this->command->info('Level-5 groups and ledgers seeding complete.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Seeding failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
