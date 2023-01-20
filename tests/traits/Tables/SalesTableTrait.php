<?php

declare(strict_types=1);

namespace Keboola\DbExtractor\TraitTests\Tables;

use Keboola\DbExtractor\TraitTests\AddConstraintTrait;
use Keboola\DbExtractor\TraitTests\CreateTableTrait;
use Keboola\DbExtractor\TraitTests\InsertRowsTrait;

trait SalesTableTrait
{
    use CreateTableTrait;
    use InsertRowsTrait;
    use AddConstraintTrait;

    public function createSalesTable(string $name = 'sales'): void
    {
        $this->createTable($name, $this->getSalesColumns());
    }

    public function generateSalesRows(string $tableName = 'sales'): void
    {
        $data = $this->getSalesRows();
        $this->insertRows($tableName, $data['columns'], $data['data']);
    }

    public function addSalesConstraint(string $tableName = 'sales', array $primaryKey = []): void
    {
        if ($primaryKey) {
            $this->addConstraint(
                $tableName,
                'PK_' . $tableName,
                'PRIMARY KEY',
                implode(', ', $primaryKey)
            );
        }

        if ($tableName === 'sales2') {
            $this->addConstraint(
                $tableName,
                'FK_sales_sales2',
                'FOREIGN KEY',
                '"createdat"',
                'sales(createdat)'
            );
        }
    }

    private function getSalesRows(): array
    {
        // phpcs:disable Generic.Files.LineLength
        return [
            'columns' => ['usergender','usercity','usersentiment','zipcode','sku','createdat','category','price','county','countycode','userstate','categorygroup'],
            'data' => [
                ['Female','Mize','-1','39153','ZD111318','2013-09-23 22:38:29','Cameras','708','Smith','28129','Mississippi','Electronics'],
                ['Male','The Lakes','1','89124','ZD111402','2013-09-23 22:38:30','Televisions','1546','Clark','32003','Nevada','Electronics'],
                ['Male','Baldwin','1','21020','ZD111483','2013-09-23 22:38:31','Loose Stones','1262','Baltimore','24005','Maryland','Jewelry'],
                ['Female','Archbald','1','18501','ZD111395','2013-09-23 22:38:32','Stereo','104','Lackawanna','42069','Pennsylvania','Electronics'],
                ['Male','Berea','0','44127','ZD111451','2013-09-23 22:38:33','Earings','1007','Cuyahoga','39035','Ohio','Jewelry'],
                ['Male','Baldwin','0','21219','ZD111471','2013-09-23 22:38:34','Jewelry Boxes','103','Baltimore','24005','Maryland','Jewelry'],
                ['Male','Phoenix','1','85083','ZD111228','2013-09-23 22:38:35','Reference','18','Maricopa','04013','Arizona','Books'],
                ['Female','Martinsburg','-1','25428','ZD111340','2013-09-23 22:38:36','Dvd/Vcr Players','197','Berkeley','54003','West Virginia','Electronics'],
                ['Male','Los Angeles','0','91328','ZD111595','2013-09-23 22:38:37','Maternity','67','Los Angeles','06037','California','Women'],
                ['Female','Bolton','1','28455','ZD111577','2013-09-23 22:38:38','Dresses','61','Columbus','37047','North Carolina','Women'],
                ['Female','Cranford','-1','07974','ZD111274','2013-09-23 22:38:39','Sports','17','Union','34039','New Jersey','Books'],
                ['Female','Troy','1','48359','ZD111146','2013-09-23 22:38:40','Cooking','6','Oakland','26125','Michigan','Books'],
                ['Female','East Mansfield','1','02764','ZD111542','2013-09-23 22:38:41','Pants','99','Bristol','25005','Massachusetts','Men'],
                ['Male','Bixby','0','74156','ZD111603','2013-09-23 22:38:42','Maternity','65','Tulsa','40143','Oklahoma','Women'],
                ['Female','Buena Park','0','92806','ZD111450','2013-09-23 22:38:43','Earings','1808','Orange','06059','California','Jewelry'],
                ['Male','Annapolis Junction','0','21029','ZD111548','2013-09-23 22:38:44','Pants','52','Howard','24027','Maryland','Men'],
                ['Male','Bellarthur','-1','27837','ZD111577','2013-09-23 22:38:45','Dresses','61','Pitt','37147','North Carolina','Women'],
                ['Male','Longmont','1','80615','ZD111438','2013-09-23 22:38:46','Diamonds','1057','Weld','08123','Colorado','Jewelry'],
                ['Female','Allenton','0','63126','ZD111296','2013-09-23 22:38:47','Audio','909','St. Louis','29189','Missouri','Electronics'],
                ['Female','Red Creek','-1','14551','ZD111287','2013-09-23 22:38:48','Audio','542','Wayne','36117','New York','Electronics'],
                ['Male','Knightstown','-1','47351','ZD111532','2013-09-23 22:38:49','Accessories','68','Henry','18065','Indiana','Men'],
                ['Female','Kimball','0','56372','ZD111535','2013-09-23 22:38:50','Accessories','69','Stearns','27145','Minnesota','Men'],
                ['Female','Brighton','1','48836','ZD111227','2013-09-23 22:38:51','Reference','12','Livingston','26093','Michigan','Books'],
                ['Female','Coloma','-1','96155','ZD111113','2013-09-23 22:38:52','Arts','11','El Dorado','06017','California','Books'],
                ['Male','Basehor','0','66027','ZD111391','2013-09-23 22:38:53','Stereo','125','Leavenworth','20103','Kansas','Electronics'],
                ['Male','Alma Center','0','54643','ZD111232','2013-09-23 22:38:54','Romance','14','Jackson','55053','Wisconsin','Books'],
                ['Female','Monroe','0','71291','ZD111431','2013-09-23 22:38:55','Diamonds','1763','Ouachita','22073','Louisiana','Jewelry'],
                ['Male','Arlington Heights','0','60630','ZD111287','2013-09-23 22:38:56','Audio','542','Cook','17031','Illinois','Electronics'],
                ['Male','Englewood','1','34231','ZD111135','2013-09-23 22:38:57','Computers','20','Sarasota','12115','Florida','Books'],
                ['Female','Allen Park','1','48210','ZD111201','2013-09-23 22:38:58','Mystery','11','Wayne','26163','Michigan','Books'],
                ['Female','Los Angeles','1','91328','ZD111423','2013-09-23 22:38:59','Bracelets','1540','Los Angeles','06037','California','Jewelry'],
                ['Female','Los Angeles','-1','91386','ZD111512','2013-09-23 22:39:00','Rings','2717','Los Angeles','06037','California','Jewelry'],
                ['Male','Charlottesville','1','22940','ZD111374','2013-09-23 22:39:01','Portable','105','Albemarle','51003','Virginia','Electronics'],
                ['Female','Los Angeles','0','92119','ZD111455','2013-09-23 22:39:02','Gold','1987','San Diego County','06073','California','Jewelry'],
                ['Female','Bay Pines','0','33755','ZD111605','2013-09-23 22:39:03','Maternity','76','Pinellas County','12103','Florida','Women'],
                ['Female','Cornelius','1','28289','ZD111243','2013-09-23 22:39:04','Science','12','Mecklenburg','37119','North Carolina','Books'],
                ['Male','Clifton','1','07506','ZD111185','2013-09-23 22:39:05','History','6','Passaic','34031','New Jersey','Books'],
                ['Female','Corona','-1','92518','ZD111281','2013-09-23 22:39:06','Travel','6','Riverside','06065','California','Books'],
                ['Male','Edgard','-1','70049','ZD111253','2013-09-23 22:39:07','Science','19','St. John the Baptist','22095','Louisiana','Books'],
                ['Female','Durham','0','18951','ZD111238','2013-09-23 22:39:08','Romance','10','Bucks','42017','Pennsylvania','Books'],
                ['Male','Copperas Cove','-1','76558','ZD111184','2013-09-23 22:39:09','History','14','Coryell','48099','Texas','Books'],
                ['Female','Brookville','0','45403','ZD111161','2013-09-23 22:39:10','Entertainments','11','Montgomery','39113','Ohio','Books'],
                ['Male','Los Angeles','0','90034','ZD111455','2013-09-23 22:39:11','Gold','1987','Los Angeles','06037','California','Jewelry'],
                ['Male','Arkansas City','0','72379','ZD111118','2013-09-23 22:39:12','Arts','9','Desha','05041','Arkansas','Books'],
                ['Female','Edgewood','1','52042','ZD111451','2013-09-24 08:27:31','Earings','1007','Clayton','19043','Iowa','Jewelry'],
                ['Male','Eastwood','-1','40285','ZD111426','2013-09-24 08:27:34','Bracelets','202','Jefferson','21111','Kentucky','Jewelry'],
                ['Female','Catoosa','1','74015','ZD111500','2013-09-24 08:27:35','Pendants','1989','Rogers','40131','Oklahoma','Jewelry'],
                ['Female','Roy','1','84402','ZD111392','2013-09-24 08:27:36','Stereo','160','Weber','49057','Utah','Electronics'],
                ['Female','Los Angeles','-1','91507','ZD111524','2013-09-24 08:27:37','Womens Watch','2818','Los Angeles','06037','California','Jewelry'],
                ['Female','Lightfoot','-1','23694','ZD111407','2013-09-24 08:27:38','Televisions','592','York','51199','Virginia','Electronics'],
                ['Female','Butler','0','16046','ZD111436','2013-09-24 08:27:39','Diamonds','1813','Butler','42019','Pennsylvania','Jewelry'],
                ['Male','Ludlow','-1','61880','ZD111424','2013-09-24 08:27:40','Bracelets','1903','Champaign','17019','Illinois','Jewelry'],
                ['Female','Los Angeles','0','92038','ZD111303','2013-09-24 08:27:41','Camcorders','612','San Diego County','06073','California','Electronics'],
                ['Female','Cornelius','0','28217','ZD111138','2013-09-24 08:27:42','Computers','19','Mecklenburg','37119','North Carolina','Books'],
                ['Male','Allenton','0','63132','ZD111180','2013-09-24 08:27:43','History','14','St. Louis','29189','Missouri','Books'],
                ['Female','Hattiesburg','0','39402','ZD111266','2013-09-24 08:27:44','Sports','13','Forrest County','28035','Mississippi','Books'],
                ['Male','Nantucket','1','02564','ZD111292','2013-09-24 08:27:45','Audio','544','Nantucket','25019','Massachusetts','Electronics'],
                ['Female','Dania','0','33309','ZD111278','2013-09-24 08:27:46','Travel','9','Broward','12011','Florida','Books'],
                ['Female','Jamaica','-1','14215','ZD111127','2013-09-24 08:27:47','Business','15','Erie County','36029','New York','Books'],
                ['Female','Bend','0','97707','ZD111183','2013-09-24 08:27:48','History','11','Deschutes','41017','Oregon','Books'],
                ['Female','Dania','-1','33326','ZD111518','2013-09-24 08:27:49','Womens Watch','1586','Broward','12011','Florida','Jewelry'],
                ['Male','Dania','-1','33339','ZD111142','2013-09-24 08:27:50','Computers','11','Broward','12011','Florida','Books'],
                ['Female','West Palm Beach','0','33454','ZD111308','2013-09-24 08:27:51','Camcorders','565','Palm Beach','12099','Florida','Electronics'],
                ['Male','Batesburg','-1','29169','ZD111369','2013-09-24 08:27:52','Portable','123','Lexington','45063','South Carolina','Electronics'],
                ['Female','Atlas','0','48458','ZD111316','2013-09-24 08:27:53','Cameras','913','Genesee','26049','Michigan','Electronics'],
                ['Female','Andale','1','67202','ZD111364','2013-09-24 08:27:55','Portable','196','Sedgwick','20173','Kansas','Electronics'],
                ['Female','Discovery Bay','-1','94507','ZD111458','2013-09-24 08:27:56','Gold','749','Contra Costa County','06013','California','Jewelry'],
                ['Female','Monroe','0','71294','ZD111598','2013-09-24 08:27:57','Maternity','78','Ouachita','22073','Louisiana','Women'],
                ['Female','Bass','-1','72628','ZD111329','2013-09-24 08:27:58','Disk Drives','113','Newton County','05101','Arkansas','Electronics'],
                ['Male','Baldwin City','-1','66046','ZD111316','2013-09-24 08:27:59','Cameras','913','Douglas','20045','Kansas','Electronics'],
                ['Female','Centreville','-1','22109','ZD111181','2013-09-24 08:28:00','History','13','Fairfax','51059','Virginia','Books'],
                ['Male','Allenton','-1','63127','ZD111592','2013-09-24 08:28:01','Fragrances','71','St. Louis','29189','Missouri','Women'],
                ['Female','Okemos','1','48911','ZD111331','2013-09-24 08:28:02','Dvd/Vcr Players','104','Ingham','26065','Michigan','Electronics'],
                ['Female','Denver','0','80219','ZD111177','2013-09-24 08:28:03','History','20','Denver','08031','Colorado','Books'],
                ['Male','Gainesville','1','32606','ZD111167','2013-09-24 08:28:04','Fiction','12','Alachua','12001','Florida','Books'],
                ['Female','Troy','0','48086','ZD111478','2013-09-24 08:28:05','Loose Stones','570','Oakland','26125','Michigan','Jewelry'],
                ['Female','Bay Minette','-1','36550','ZD111425','2013-09-24 08:28:06','Bracelets','870','Baldwin','01003','Alabama','Jewelry'],
                ['Female','Arab','1','35957','ZD111236','2013-09-24 08:28:07','Romance','6','Marshall','01095','Alabama','Books'],
                ['Male','Hialeah','0','33128','ZD111190','2013-09-24 08:28:08','Home Repair','13','Miami-Dade','12086','Florida','Books'],
                ['Female','Sacramento','1','95887','ZD111605','2013-09-24 08:28:09','Maternity','76','Sacramento','06067','California','Women'],
                ['Male','McHenry','-1','62034','ZD111246','2013-09-24 08:28:10','Science','11','Madison County','17119','Illinois','Books'],
                ['Female','Philadelphia','-1','19128','ZD111400','2013-09-24 08:28:11','Televisions','1883','Philadelphia','42101','Pennsylvania','Electronics'],
                ['Female','Bradley','0','93923','ZD111166','2013-09-24 08:28:12','Fiction','15','Monterey','06053','California','Books'],
                ['Female','Gering','-1','69358','ZD111528','2013-09-24 08:28:13','Womens Watch','1165','Scotts Bluff','31157','Nebraska','Jewelry'],
                ['Female','Los Angeles','0','90223','ZD111164','2013-09-24 08:28:14','Entertainments','10','Los Angeles','06037','California','Books'],
                ['Female','Amboy','1','98686','ZD111196','2013-09-24 08:28:15','Home Repair','20','Clark','53011','Washington','Books'],
                ['Female','Barberville','1','32724','ZD111118','2013-09-24 08:28:16','Arts','9','Volusia','12127','Florida','Books'],
                ['Male','Carrollton','0','76258','ZD111394','2013-09-24 08:28:17','Stereo','110','Denton','48121','Texas','Electronics'],
                ['Female','Saint Charles','-1','63303','ZD111292','2013-09-24 08:28:18','Audio','544','St. Charles','29183','Missouri','Electronics'],
                ['Female','Bairdford','-1','15270','ZD111141','2013-09-24 08:28:19','Computers','18','Allegheny','42003','Pennsylvania','Books'],
                ['Male','Glen Oaks','0','11447','ZD111331','2013-09-24 08:28:20','Dvd/Vcr Players','104','Queens','36081','New York','Electronics'],
                ['Female','Addison','1','75015','ZD111338','2013-09-24 08:28:21','Dvd/Vcr Players','180','Dallas','48113','Texas','Electronics'],
                ['Male','Bible School Park','1','13777','ZD111460','2013-09-24 08:28:22','Gold','728','Broome','36007','New York','Jewelry'],
                ['Male','Jersey','1','30025','ZD111313','2013-09-24 08:28:23','Cameras','523','Walton','13297','Georgia','Electronics'],
                ['Male','Hanna','-1','46346','ZD111446','2013-09-24 08:28:24','Earings','313','LaPorte','18091','Indiana','Jewelry'],
                ['Male','Saint Paul','1','55375','ZD111466','2013-09-24 08:28:25','Jewelry Boxes','186','Hennepin','27053','Minnesota','Jewelry'],
                ['Female','Glen Oaks','1','11366','ZD111178','2013-09-24 08:28:26','History','9','Queens','36081','New York','Books'],
                ['Male','Alexandria','-1','17260','ZD111560','2013-09-24 08:28:27','Shirts','67','Huntingdon','42061','Pennsylvania','Men'],
                ['Male','Los Angeles','0','92124','ZD111132','2013-09-24 08:28:28','Business','12','San Diego County','06073','California','Books'],
                ['usergender','usercity','','zipcode','sku','createdat','category','','county','countycode','userstate','group'],
            ],
        ];
        // phpcs:enable
    }

    private function getSalesColumns(): array
    {
        return [
            'usergender' => 'varchar(4095) NULL',
            'usercity' => 'varchar(4095) NULL',
            'usersentiment' => 'varchar(4095) NULL',
            'zipcode' => 'varchar(4095) NULL',
            'sku' => 'varchar(4095) NULL',
            'createdat' => 'varchar(64) NOT NULL',
            'category' => 'varchar(4095) NULL',
            'price' => 'varchar(4095) NULL',
            'county' => 'varchar(4095) NULL',
            'countycode' => 'varchar(4095) NULL',
            'userstate' => 'varchar(4095) NULL',
            'categorygroup' => 'varchar(4095) NULL',
        ];
    }
}