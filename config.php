<?php
/**
 * Created by PhpStorm.
 * User: Genesis
 * Date: 2/25/2017
 * Time: 4:10 PM
 */

define('ROOT', __DIR__ ."/" );
define('BASE_PATH', str_replace('/include/', '', dirname(__FILE__)));
define('HTTP_PATH_ROOT', "http://".$_SERVER["HTTP_HOST"]."/drsho/");
define('INCLUDE_PATH', "http://".$_SERVER["HTTP_HOST"]."/drsho/include/");

//Remote
//define(DBHOST, "mpwebservicesnet.ipagemysql.com");
//define(DBUSER, "drsho");
//define(DBPASS, "metcs633");
//define(DBDATABASE, "drsho");

//Localhost
define(DBHOST, "localhost");
define(DBUSER, "drsho");
define(DBPASS, "metcs633");
define(DBDATABASE, "drsho_drsho");


define(TGL, "Triglycerides");
define(LDL, "LDL");
define(HDL, "HDL");
define(CHL, "Cholesterol");

define(LDL_MIN, 50);
define(LDL1, 99);
define(LDL2, 129);
define(LDL3, 159);
define(LDL4, 189);
define(LDL5, 300);
define(LDL_MAX, 300);

define(HDL_MIN, 20);
define(HDL1, 39);
define(HDL2, 59);
define(HDL3, 90);
define(HDL_MAX, 90);

define(TGL_MIN, 0);
define(TGL1, 149);
define(TGL2, 199);
define(TGL3, 499);
define(TGL4, 1000);
define(TGL_MAX, 1000);

define(CHL_MIN, 80);
define(CHL1, 200);
define(CHL2, 139);
define(CHL3, 500);
define(CHL_MAX, 500);

//Text phrases
define(LDL1_TEXT, "Ideal");
define(LDL2_TEXT, "Close to Ideal");
define(LDL3_TEXT, "Borderline High");
define(LDL4_TEXT, "High");
define(LDL5_TEXT, "Very High");

define(HDL1_TEXT, "Low (high heart disease risk)");
define(HDL2_TEXT, "Normal (but the higher the better)");
define(HDL3_TEXT, "Best (offers protection against heart disease)");

define(TGL1_TEXT, "Normal");
define(TGL2_TEXT, "Borderline High");
define(TGL3_TEXT, "High");
define(TGL4_TEXT, "Very High");

define(CHL1_TEXT, "Ideal");
define(CHL2_TEXT, "Border High");
define(CHL3_TEXT, "High");


define(NORMAL_HEALTH,"Your health is excellent!  Keep doing what you're doing.");
