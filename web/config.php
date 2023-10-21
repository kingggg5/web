<?php

$config = array(); //ห้ามแก้ไข
//การเชื่อมต่อฐานข้อมูล php sqlsrv
$config['mssql_host'] = "localhost,1999";
$config['mssql_user'] = "sa";
$config['mssql_password'] = "returnsql2018";
$config['db_name'] = "winz";
// *** คำเดือนโปรดลงฟังชั่นต่างๆให้ครบก่อนใช้งานเว็บในโฟลเดอร์ install
//ค่า boolean ใน php เพื่อการใช้เปิด/ปิดระบบต่างๆของเว็บ
/*
  true = จริง
  false = เท็จ
 */

$config['format_email_login'] = true; //รูปแบบการล็อกอิน email
$config['captcha_login'] = true; //ป้องกันหุนยนต์

$config['login_md5'] = true; //การล็อกอินหรือสมัครบัญชีผู้ใช้ dbo.Acoounts colum MD5Password ถ้าไม่มีการเข้ารหัส md5 ปรับเป็น false
$config['login_md5_salt'] = 'g5sc4gs1fz0h';

//AUTO Site to ssl
$config['auto_ssl'] = false; //บังคับผู้ใช้ SSL
$config['auto_ssl_url'] = "https://mc-paragraph.ddns.net/2017z/"; //domain ที่มีใบรองรับ SSL Cerficate

$config['Merchants_List'] = false; //หน้าพ่อค้าถ้าเปิดแก้รายชื่อใน Merchants_List.php
//Register with Facebook account ถ้าเปิดระบบนี้ต้องเปิด $config['auto_ssl'] = true และ ต้องมีใบรองรับ SSL Cerficate (ความเป็นส่วนตัวของผู้ใช้)
$config['fb']['regis'] = false;
$config['fb_app_id'] = '174822433155192';
$config['registerfb_accounstatus'] = 100;
$config['registerfb_AccountType'] = 2;
//$config['registerfb_friends'] = 350; //จำนวนเพื่อนในเฟสบุค เท่านี้ถึงสมัครได้

//ANTICOPY NOOB PROGRAMER
$config['anticopy_noob_programer'] = false; //กากเกิ้น
//Ctrl+U, Ctrl+Shift+J, Ctrl+F, F12, F3 to link
$config['shortcut_link'] = false;
$config['shortcut_to_link'] = "https://www.shafou.com"; //แฮร่

$config['reputation_system'] = true; //ระบบรียศด้วย GC
$config['reputation_price'] = 200;
$config['reputation_set'] = 0;

$config['reward_system'] = true; //ระบบรับไอเท็มด้วยเวลาออนไลน์
$config['reward_backend_isDev'] = 126;
$config['reward_gc']['item_id'] = 999;

$config['map_tp'] = true; //ระบบย้ายแมพ
$config['map_tp_price'] = 500; //GC ที่เสียต่อการย้าย 1 แมพ
//map_city.setting.php ตั้งค่าการ TP แต่ละแมพที่นี้ ก็อฟโค้ดวางต่อๆกันแล้วเปลี่ยนพิกัด

$config['topup_rename'] = true; //ระบบเปลี่ยนชื่อตัวละร
$config['char_rename_language'] = 1; // 0 = ตัวอักขระอะไรก็ได้, 1 = English, 2 = TH/ENG
$config['char_rename_maxleng'] = 32;
$config['topup_rename_price'] = 0; //0 = เปลี่ยนชื่อฟรี

$config['case_random'] = true; //ระบบสุ่มไอเท็ม
$config['case_random_sound'] = true; // เอาเสียงใส่ที่ /sounds/case
$config['case_random_sound_amount'] = 9; //จำนวนเสียงที่สุ่ม มันสุ่มเสียงหลังสุ่มไอเท็มเสร็จ เอาไฟล์ .ogg ใส่ตามจำนวนที่ใส่ตรงนี้ เช่น 1.ogg และ 2.ogg และ ... ห้ามใส่น้อยกว่า 1 ถ้าเปิดระบบเสียง
$config['case_random_price'] = 1; //ราคาเป็นตั่ว 1 ครั้งต่อการสุ่ม
$config['case_random_amount'] = 3; //ใส่จำนวนแพ็คที่จะสุ่ม และตรง html ด้วย (No backend)
//ราคาบัตรได้รับตั่วตามนี้
$config['case_random_amount_topup']['50'] = 1;
$config['case_random_amount_topup']['90'] = 2;
$config['case_random_amount_topup']['150'] = 3;
$config['case_random_amount_topup']['300'] = 4;
$config['case_random_amount_topup']['500'] = 5;
$config['case_random_amount_topup']['1000'] = 6;
//html บอกผู้เล่นตอนสุ่มใส่ตาม $config['case_random_amount'] ขนาดรูปที่ใช้ 150 * 800 ลงบอกแพ็คตรงนี้และใส่ของใน navicat ตรง ฟั่งชั่น WZ_CASE2018
$config['case_random_pack'] = '
<h5 style="color: white;">แพ็คที่ 1</h5>
<img src="img/case/p1.png">
 <h5 style="color: white;">แพ็คที่ 2</h5>
<img src="img/case/p2.png">
<h5 style="color: white;">แพ็คที่ 3</h5>
<img src="img/case/p3.png">
<hr>
';

//การเชื่อมต่อเซิร์ฟเวอร์ เข้าสู่ระบบรูปแบบ E-mail WALLET.TRUEMONEY.COM
$config['trueID'] = "namememeee@2323aasd.com";
$config['truePS'] = "gapommz382832";
$config['maxleng']['truemoney'] = 14;

$config['true_topup_gc'] = true; //เติมเงินได้รับ GC
$config['true_topup_item_more'] = true; //แก้ไขใน WZ_TOPUP2017 function ถ้าเปิด
$config['Promotion'] = true; //ประกาศโปรโมชั่นเมื่อถึงวันเติมเงิน * มากกว่า 1 รูปภาพระบบจะเช็กให้เองใส่รูปใน img/gcx{จำนวนการคูณ}.png เช่น วันจันทร์(Mon) เป็น 5 จะโชว์รูปใน img/gcx5.png
$config['true_topup_promotion_day'] = array(/* อัตราการเติมเงินคูณในแต่ละวัน 1 คือปกติ จะไม่มีรูปภาพถ้าเปิด $config['Promotion'] */
    "Mon" => 1,
    "Tue" => 1,
    "Wed" => 1,
    "Thu" => 1,
    "Fri" => 1,
    "Sat" => 2,
    "Sun" => 2
);
#*** อัตราการเติมเงินด้วยบัตรทรูมันนี่ ได้รับ GC
$config['true_topup_gc_amount']['50'] = 500;
$config['true_topup_gc_amount']['90'] = 900;
$config['true_topup_gc_amount']['150'] = 1500;
$config['true_topup_gc_amount']['300'] = 3000;
$config['true_topup_gc_amount']['500'] = 5000;
$config['true_topup_gc_amount']['1000'] = 10000;

//ตำเดือนโปรดติดตั้ง sql dbo และ Function ให้ครบก่อนใช้งาน อยู่ใน โพลเดอร์ install
/*
ติดต่อผู้พัฒนาแอพพลิเคชั่นนี้
ช่องที่ 1 แนะนำ
https://www.facebook.com/aukarapol.sangnoi
ช่องทางที่ 2
เบอร์โทรศัพท์ส่วนตัว : 0803073566 (คุณกุ)
*/