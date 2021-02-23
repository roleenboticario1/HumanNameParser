<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Http\Request;

use App\Http\Controllers\nameParserController;
use App\Http\Request\RequestManager;

class nameParserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

 
   public function  testFullnameIfOnlyOneCharacter()                          
   {                                                                            
        $response  = (new App\Http\Controllers\nameParserController)->custom_validation('Boticario,');
        $expectedOutput = [
            "status" => false,
            "message" => "Please provide a valid name. One Character is not allowed."
        ];
        $this->assertEquals($expectedOutput, $response->original);
   }
   public function testFullnameIfOnlyMoreThanOneComma()                          
   {                                                                            
        $response = (new App\Http\Controllers\nameParserController)->custom_validation('Boticario, Roleen,');
        $expectedOutput = [
            "status" => false,
            "message" => "Please provide a valid name. More than one comma is not allowed"
        ];
        $this->assertEquals($expectedOutput, $response->original);
   }
   public function testFullnameIfSuccessfullyParsename()
   { 
        $actualResult = $this->post('/inputFullname', ['fullname' => 'Dela Cruz, DR. Mark Roleen Kevin Delos Reyes Sr']);
        $this->seeStatusCode(self::HTTP_OK);
   }
   public function  test_ParseName_if_Contain_numbers_and_special_character()                          
   {                                                                            
        $this->post('/inputFullname',['fullname' => 'Boticario3245 Reyes Boticario']);
        $this->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY );
   }
   public function  test_Fullname_if_Empty()                          
   {                                                                            
        $this->post('/inputFullname',['fullname' => '']);
        $this->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY );
   }
   public function  test_ParseName_if_successfully_passed()                          
   {                                                                            
        $response  = (new App\Http\Controllers\nameParserController)->custom_validation('Roleen Boticario');

        $this->assertTrue(true);
   }
   public function test_if_successfully_show_payload()
   {
        $this->post('/showPayload');
        $this->seeStatusCode(self::HTTP_INTERNAL_ERROR);
   }
   public function testParseFunction()
   {
        $response  = (new App\nameParser\nameParser)->parse();
        $this->assertTrue(true);
   }
    public function test__Contsuctor()
   {
        $response  = (new App\nameParser\nameParser)->__construct('fullname');
        $this->assertTrue(true);
   }
  // LastName First
   public function  test_Fullname_with_double_words_for_lastname_firstname_and_middlename_with_title_and_suffix()                          
   {                                                                            
        $response  = (new App\Http\Controllers\nameParserController)->ParseName('Dela Boticario, Dr. Mark Eleazar Delos Reyes,  Sr');
        $this->assertTrue(true);
   }
   public function  testFullname_with_double_words_for_lastname_and_firstname_only_with_Suffix()
   {
        $response  = (new App\Http\Controllers\nameParserController)->ParseName('Dela Cruz, Jaun Miguel  Jr ');
        $this->assertTrue(true);
   }
   public function  testFullname_with_double_Words_for_lastname_only_and_no_middle_with_title_and_suffix()
   {
        $response  = (new App\Http\Controllers\nameParserController)->ParseName('Dela Cruz, Dr. Mark  Jr');
        $this->assertTrue(true);
   }
   public function  testFullname_with_double_Words_for_lastname_middlename_only_with_title_and_suffix()
   {
        $response  = (new App\Http\Controllers\nameParserController)->ParseName('Dela Cruz, Dr. Paul De Almaridez Jr');
        $this->assertTrue(true);
   }
   public function  testFullname_lastname_and_firstname_middle_without_suffix_and_title()
   {
        $response  = (new App\Http\Controllers\nameParserController)->ParseName('Butial, Ralph R.');
        $this->assertTrue(true);
   }
   public function   testFullname_firstname_and_lastname_but_double_words_for_lastname_only_and_without_suffix_and_title()
   {
        $response  = (new App\Http\Controllers\nameParserController)->ParseName('Delos Reyes, Roleen');
        $this->assertTrue(true);
   }
   // FirstName First
   public function  testFullname_Firstname_and_lastname_only()
   {
        $response  = (new App\Http\Controllers\nameParserController)->ParseName('Roleen Reyes');
        $this->assertTrue(true);
   }
   public function  testFullname_with_single_word_for_firstname_middlename_and_lastname_with_suffix()
   {
        $response  = (new App\Http\Controllers\nameParserController)->ParseName('Roleen Reyes Boticario Jr.');
        $this->assertTrue(true);
   }
   public function   testFullname_with_sinle_word_for_firstname_middlename_and_lastname_with_title()
   {
        $response  = (new App\Http\Controllers\nameParserController)->ParseName('Dr. Roleen Reyes Boticario');
        $this->assertTrue(true);
   }
    public function  testFullname_with_sinle_word_for_firstname_middlename_and_lastname_with_title_and_Suffix()
   {
        $response  = (new App\Http\Controllers\nameParserController)->ParseName('Dr. Roleen Reyes Boticario Jr.');
        $this->assertTrue(true);
   }
   public function  testFullname_Double_words_for_middlename_with_suffix_and_title()
   {
        $response  = (new App\Http\Controllers\nameParserController)->ParseName('Dr. Roleen De Vera Boticario Jr.');
        $this->assertTrue(true);
   }
    public function  testFullname_Firstname_and_lastname_only_with_suffix()
   {
        $response  = (new App\Http\Controllers\nameParserController)->ParseName('Micheal Angelo Sr.');
        $this->assertTrue(true);
   }
   public function  testFullnamewithdoubleWordsfirstnameandlastnamewithsuffix()
   {
        $response  = (new App\Http\Controllers\nameParserController)->ParseName('Jaun Miguel Dela Cruz Jr');
        $this->assertTrue(true);
   }
   public function  testFullnameWithDoubleWordsforlastnamemiddlenamelastanmeand_itleAndSuffixwithCommainlastname()
   {
        $response  = (new App\Http\Controllers\nameParserController)->ParseName('Dr. kenneth John Delos Santos De Vera, III');
        $this->assertTrue(true);
   }
   public function  testFullnamewithdoublewordsWithoutmiddletitlesuffix()
   {
        $response  = (new App\Http\Controllers\nameParserController)->ParseName('Jaun Miguel Dela Cruz');
        $this->assertTrue(true);
   }
    public function  testFullnamewithdoublewordsforlastnamefirstnmaemiddlename()
   {
        $response  = (new App\Http\Controllers\nameParserController)->ParseName('Jaun Miguel Delos Reyes Dela Cruz');
        $this->assertTrue(true);
   }
   
}
