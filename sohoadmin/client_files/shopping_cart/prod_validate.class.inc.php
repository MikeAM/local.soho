<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


class CreditCardValidationSolution {

   function CCValidationSolution($Accepted) {

      $this->CCVSNumberLeft = '';
      $this->CCVSNumberRight = '';
      
//      echo "\$Accepted:<br>";
//      foreach ( $Accepted as $var=>$val ) {
//         echo $var."=".$val."<br>";
//      }

      #  Avoid script dumping due to programming errors.
      if ( !is_array($Accepted) ) {
         $this->CCVSError = 'The programmer improperly used the Accepted argument.';
         return FALSE;
      }

      #  Catch malformed input.
      if ( empty($this->CCVSNumber) OR !is_string($this->CCVSNumber) ) {
         $this->CCVSNumber = '';
         $this->CCVSError = "The number submitted wasn't a string.";
         return FALSE;
      }
      
      //echo "Number: '".$this->CCVSNumber."'<br>";
      
      #  Ensure number doesn't overflow.
      $this->CCVSNumber = substr($this->CCVSNumber, 0, 30);

      //echo "Number: '".$this->CCVSNumber."'<br>";
      
      #  Remove non-numeric characters.
      $this->CCVSNumber = ereg_replace('[^0-9]', '', $this->CCVSNumber);
      
      //echo "Number: '".$this->CCVSNumber."'<br>"; 
      
      #  Set up variables.
      $this->CCVSNumberLeft = substr($this->CCVSNumber, 0, 4);
      $this->CCVSNumberRight = substr($this->CCVSNumber, -4);
      $NumberLength = strlen($this->CCVSNumber);

      #  Determine the card type and appropriate length.
      if ($this->CCVSNumberLeft >= 3000 and $this->CCVSNumberLeft <= 3059) {
         $this->CCVSType = 'Diners Club';
         $ShouldLength = 14;
      } elseif ($this->CCVSNumberLeft >= 3600 and $this->CCVSNumberLeft <= 3699) {
         $this->CCVSType = 'Diners Club';
         $ShouldLength = 14;
      } elseif ($this->CCVSNumberLeft >= 3800 and $this->CCVSNumberLeft <= 3889) {
         $this->CCVSType = 'Diners Club';
         $ShouldLength = 14;

      } elseif ($this->CCVSNumberLeft >= 3400 and $this->CCVSNumberLeft <= 3499) {
         $this->CCVSType = 'American Express';
         $ShouldLength = 15;
      } elseif ($this->CCVSNumberLeft >= 3700 and $this->CCVSNumberLeft <= 3799) {
         $this->CCVSType = 'American Express';
         $ShouldLength = 15;

      } elseif ($this->CCVSNumberLeft >= 3528 and $this->CCVSNumberLeft <= 3589) {
         $this->CCVSType = 'JCB';
         $ShouldLength = 16;

      } elseif ($this->CCVSNumberLeft >= 3890 and $this->CCVSNumberLeft <= 3899) {
         $this->CCVSType = 'Carte Blanche';
         $ShouldLength = 14;

      } elseif ($this->CCVSNumberLeft >= 4000 and $this->CCVSNumberLeft <= 4999) {
         $this->CCVSType = 'Visa';
         if ($NumberLength > 14) {
            $ShouldLength = 16;
         } elseif ($NumberLength < 14) {
            $ShouldLength = 13;
         } else {
            $this->CCVSError = ''.lang("Visa usually has 16 or 13 digits").'. '.lang("You entered 14").'.';
            return FALSE;
         }

      } elseif ($this->CCVSNumberLeft >= 5100 and $this->CCVSNumberLeft <= 5599) {
         $this->CCVSType = 'MasterCard';
         $ShouldLength = 16;

      } elseif ($this->CCVSNumberLeft == 5610) {
         $this->CCVSType = 'Australian BankCard';
         $ShouldLength = 16;

      } elseif ($this->CCVSNumberLeft == 6011) {
         $this->CCVSType = 'Discover/Novus';
         $ShouldLength = 16;

      } else {
         $this->CCVSType = '';
         $this->CCVSError = lang("First four digits").", $this->CCVSNumberLeft, ".lang("indicate we don't accept that type of card").".";
         return FALSE;
      }

      #  Do you accpet this type of card?
      if ($Accepted[0] != 'All') {
         $Accept = 0;
         while ( list(,$Type) = each($Accepted) ) {
            if ($Type == $this->CCVSType) {
               $Accept = 1;
            }
         }
         if (!$Accept) {
            $this->CCVSError = lang("We don't accept")." $this->CCVSType ".lang("cards").".";
            return FALSE;
         }
      }

      #  Is the length correct?
      if ($NumberLength <> $ShouldLength) {
         $Missing = $NumberLength - $ShouldLength;
         if ($Missing < 0) {
            $this->CCVSError = lang("Number is missing")." " . abs($Missing) . " ".lang("digit(s)").".";
         } else {
            $this->CCVSError = lang("Number has")." $Missing ".lang("too many digit(s)").".";
         }
         return FALSE;
      }

      #  Start the Mod10 checksum process...
      $Checksum = 0;

      #  Add even digits in even length strings
      #  or odd digits in odd length strings.
      for ($Location = 1 - ($NumberLength % 2); $Location < $NumberLength; $Location += 2) {
         $Checksum += substr($this->CCVSNumber, $Location, 1);
      }

      #  Analyze odd digits in even length strings
      #  or even digits in odd length strings.
      for ($Location = ($NumberLength % 2); $Location < $NumberLength; $Location += 2) {
         $Digit = substr($this->CCVSNumber, $Location, 1) * 2;
         if ($Digit < 10) {
            $Checksum += $Digit;
         } else {
            $Checksum += $Digit - 9;
         }
      }

      #  If the checksum is divisible by 10, the number passes.
      if ($Checksum % 10 == 0) {
         $this->CCVSError = '';
         return TRUE;
      } else {
         $this->CCVSError = lang("Card failed the checksum test").".";
         return FALSE;
      }
   }
}


?>
