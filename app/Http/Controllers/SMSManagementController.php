<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SMSManagementController extends Controller
{
    /**
     * This will display the view for the sms manipulation
     */
    public function showSMSPage(){
        return view("sms");
    }

    /**
     * This will help calculate the sms charges and using the total pages and the numbers of corresponding receipients
     */
    public function calculateSMSCharges(Request $request)
    {
        try{
            $priceLists = $this->getPriceLists();
            $sender = $request->sender;
            $message = $request->message;
            $pages = $this->countPages($message);
            $phoneNumbers = $request->phone_number;
            $phoneNumbersFormated = preg_replace("/[\s\n]+/", ",", $phoneNumbers);
            $phoneNumbersFormated = preg_replace("/,+,/", ",", $phoneNumbersFormated);//remove double commas from the formated string
     
            $phoneNumbersArrays = explode(",",$phoneNumbersFormated);
            $smsCost = 0;
            $formatedRecipients =[];
            
            foreach($phoneNumbersArrays as $phone){
               $formattedPhoneAndPrefiix = $this->formatPrefix($phone);
               $phone = $formattedPhoneAndPrefiix['phone'];
               $prefix = $formattedPhoneAndPrefiix['prefix'];
               $formatedRecipients[] = $phone;
                if(array_key_exists($prefix,$priceLists)){
                    $smsCost += $priceLists[$prefix];
                }
            }
           
            return response()->json([
                "smsCost" => round($smsCost * $pages['pageCount'],2),
                 "receivers" => $formatedRecipients,
                "pageCount" => $pages['pageCount']
            ]);

        }catch(Throwable $e){
            info($e->getMessage());
        }
    }

    /**
     * Calculate how many pages to be sent to enable us get the cost
     * @return int|float
     */
    private function countPages(string $messageTexts)
    {
        $pageCount = 1;
        $firstPage = substr($messageTexts,0,160);
        if(strlen($firstPage) ===160 && strlen($messageTexts) > 160){
            $remainingText = substr($messageTexts,strlen($firstPage));
            $otherPages = ceil(strlen($remainingText)/154);
            $pageCount += $otherPages;
        }

        return ['first_page'=> strlen( $firstPage),"pageCount"=>$pageCount];
    }

    /**
     * Get the price lists text file and convert it into an array
     * @return array
     */
    private function getPriceLists()
    {
        $data= File::get(storage_path('files/price_lists.txt'));
        $data = preg_replace("/[\s\n]+/", ",", $data);
        $priceListArray = $this->convertDataStringToArray($data);
        return $priceListArray;
    }

    /**
     * format a prefix that will correspond to the the prefixes from the price lists and also convert the phone number entered to standard sms number format for local numbers
     * @return array
     * @param string $phone
     */
    private function formatPrefix(string $phone)
    {
        $phone = preg_replace("/|\+/", "", $phone);//remove the trailling plus sign if any
        $firstLetterFromThePhoneNumber = substr($phone,0,1);

        // First check if this phone number was formatted as 2340
        if(($firstLetterFromThePhoneNumber =="2" && substr($phone,0,4) =="2340") ){
            $newFormat = "234";
            $phone = str_replace(substr($phone,0,4),$newFormat,$phone);
        }
        
        //Here, check if the format is in the order of 09, 08,and 07
        if($firstLetterFromThePhoneNumber=="0" && in_array(substr($phone,1,1),[7,9,8] )){
            $newFormat = "234";
            $replacement = preg_replace("/^0|/", "", $phone);
            $phone = "$newFormat"."$replacement";
        }
        
        //Get the prefix first but this will change when an international number is encountere
        $prefix = substr($phone,0,6);
        
        //Check for what prefix to extract for various international numbers
        if( $firstLetterFromThePhoneNumber !="0" && substr($phone,0,3) !="234"){//this is a foreign number
            if(substr($phone,0,1)==7 || substr($phone,0,1)==1){
                $prefix = substr($phone,0,1);
            }elseif(substr($phone,0,2) > 7 && substr($phone,0,2) < 100 ){
                
                $prefix = substr($phone,0,2);
            }else{
                $prefix = substr($phone,0,3);
            }
        }
        return ['phone'=> $phone,"prefix"=>$prefix];
    }

    private function convertDataStringToArray($data)
    {
        $array = [];

        // Split the data by commas
        $pairs = explode(",", $data);

        // Iterate over each pair
        foreach ($pairs as $pair) {

            $parts = explode("=", $pair);

            $prefix = $parts[0];
            $price = $parts[1];
            $array[$prefix] = (float) $price;
        }
        return $array;
    }

    private function integrateTheSMSService($sender,$receivers,$message)
    {
       $url= "https://smsclone.com/api/sms/sendsms?username=charleeo&password=rice1234&sender=@@sender@@&recipient=@@$receivers@@&message=@@$message@@";
       $sms =Http::post($url,[]);
       return $sms->status();//just testing this
    }
}

