<?php


namespace App\nameParser;

class nameParser{   
     
    private $titles;
    private $prefices;
    private $suffices;
    private $title;
    private $first;
    private $middle;
    private $last;
    private $suffix;
    private $fullName;
    private $notParseable;

    /**
    * Constructor:
    * Setup the object, initialise the variables, and if instantiated with a name - parse it automagically
    *
    * @param string The Name String
    * @access public
    */

    public function __construct( $initString = "" ){
        $this->title        = "";
        $this->first        = "";
        $this->middle       = "";
        $this->last         = "";
        $this->suffix       = "";
        $this->titles       = config('nameParser.titles');
        $this->prefices     = config('nameParser.prefices');
        $this->suffices     = config('nameParser.suffices');
        $this->fullName          = "";
        $this->notParseable      = FALSE;

        // if initialized by value, set class variable and then parse
        if ( $initString != "" ) {
            $this->fullName = $initString;
            $this->parse();
        }
    }

    /**
    * Destructor
    * @access public
    */
    public function __destruct() {}


    /**
    * Access Method
    * @access public
    */
    public function getFirstName() { return $this->first; }


    /**
    * Access Method
    * @access public
    */
    public function getMiddleName() { return $this->middle; }

    /**
    * Access Method
    * @access public
    */
    public function getLastName() { return $this->last; }

    /**
    * Access Method
    * @access public
    */
    public function getTitle() { return $this->title; }

    /**
    * Access Method
    * @access public
    */
    public function getSuffix() { return $this->suffix; }

    /**
    * Access Method
    * @access public
    */
    public function getNotParseable() { return $this->notParseable; }

    /**
    * Mutator Method
    * @access public
    * @param newFullName the new value to set fullName to
    */
    public function setFullName( $newFullName ) { $this->fullName = $newFullName; }

    /**
    * Determine if the needle is in the haystack.
    *
    * @param needle the needle to look for
    * @param haystack the haystack from which to look into
    * @access private
    */
    private function inArrayNorm( $needle, $haystack ) {
        $needle = trim( strtolower( str_replace( '.', '', $needle ) ) );
        return  in_array( $needle, $haystack );
    }

    /**
    * Extract the elements of the full name into separate parts.
    *
    * @access public
    */
    public function parse() {
        // reset values
        $this->title        = "";
        $this->first        = "";
        $this->middle       = "";
        $this->last         = "";
        $this->suffix       = "";
        $this->notParseable     = FALSE;

        // break up name based on number of commas
        $pieces      = explode(',', preg_replace('/\s+/', ' ', trim( $this->fullName ) ) );
        $numPieces  = count( $pieces );

        switch ( $numPieces ) {

            // array(title first middle last suffix)
            case   1:
                $subPieces = explode(' ', trim( $pieces[0] ) );
                $numSubPieces = count( $subPieces );
                for ( $i = 0; $i < $numSubPieces; $i++ ) 
                {
                    $current = trim( $subPieces[$i] );
                    if ( $i < ($numSubPieces-1) ) {
                        $next = trim( $subPieces[$i+1] );
                    } else {
                        $next = "";
                    }
                    if ( $i == 0 && $this->inArrayNorm( $current, $this->titles ) ) {
                        $this->title = $current;
                        continue;
                    }
                    if ( $this->first == "") {
                        $this->first = $current;
                        continue;
                    }
                    if ( $i == $numSubPieces-2 && ($next != "") && $this->inArrayNorm( $next, $this->suffices ) ) {
                        if ( $this->last != "") {
                            $this->last .=  " ".$current;
                        } else {
                            $this->last = $current;
                        }
                        $this->suffix = $next;
                        break;
                    }
                    if( $i == $numSubPieces-1 ) {
                        if ( $this->last != "") {
                            $this->last .= " ".$current;
                        } else {
                            $this->last = $current;
                        }
                        continue;
                    }
                    if( $this->inArrayNorm( $current, $this->prefices ) ) {
                        if ( $this->last == "" && $this->middle == "") {
                             $this->last .= " ".$current; 
                            $this->middle .= " ".$current; //this add prefices to middlename like delo
                        }else{
                            $this->last = $current;
                        } 
                        continue;               
                    } 
                    //display firstname without lastword of middle
                    if( $this->first != "" && $this->middle == "") {
                         $this->first .= " ".$current;
                    } 
                    //display middlename
                    if ( $this->middle != "" ) {
                        $this->middle .= " ".$current;
                    }
                     if($this->middle != "")  //delete middle name that joins lastname
                    {
                        $this->last = "";
                    }
                }
                if($this->inArrayNorm($this->middle,$this->prefices)){
                    $this->middle = "";
                }
                // handle empty middle name
                if(!$this->middle) // if middle is empty string
                {
                    $explode = explode(' ',$this->first); // break first name into array
                    $reversed = array_reverse($explode);
                    $this->middle = array_shift($reversed);
                    $reversed = array_reverse($reversed);
                    $this->first = implode(' ', $reversed);
                }
                //move the value to firstname to middleanme
                if(!$this->first)
                {
                    $explode = explode(' ',$this->middle);
                    $this->first = array_shift($explode);
                    $this->middle = implode(' ', $explode);
                }
                break;
            default:
                switch( $this->inArrayNorm( $pieces[1], $this->suffices ) ) {

                    // array(title first middle last, suffix [, suffix])
                    case    TRUE:
                        $subPieces = explode(' ', trim( $pieces[0] ) );
                        $numSubPieces = count( $subPieces );
                        for ( $i = 0; $i < $numSubPieces; $i++ ) {
                            $current = trim( $subPieces[$i] );
                            if ( $i < ($numSubPieces-1) ) {
                                $next = trim( $subPieces[$i+1] );
                            } else {
                                $next = "";
                            }
                            if ( $i == 0 && $this->inArrayNorm( $current, $this->titles ) ) {
                                $this->title = $current;
                                continue;
                            }
                            if ( $this->first == "" ) {
                                $this->first = $current;
                                continue;
                            }
                            if ( $i == $numSubPieces-1 ) {
                                if ( $this->last != "" ) {
                                    $this->last .=  " ".$current;
                                } //else {
                                    //$this->last = $current;
                                //}
                                continue;
                            }
                            if ( $this->inArrayNorm( $current, $this->prefices ) ) {
                                    if ( $this->last == "" ) {
                                     $this->last .= " ".$current;                   
                                    }// else {
                                      //  $this->last = $current;        
                                   // } eto
                                   // continue;
                                }
                            if ( $this->last != "" ) {
                                $this->last .= " ".$current;
                                continue;
                            }
                            if ( $this->middle != "" ) {
                               // eto $this->middle .= " ".$current;
                            } else {
                                $this->middle = $current;
                            }
                        }
                        break;
                    // array(last, title first middles[,] suffix [,suffix])
                    case    FALSE:
                             $subPieces = explode( ' ', trim( $pieces[1] ) );
                        $numSubPieces = count( $subPieces );
                        for ( $i = 0; $i < $numSubPieces; $i++ ) {
                           $current = trim( $subPieces[$i] );
                    if ( $i < ($numSubPieces-1) ) {
                        $next = trim( $subPieces[$i+1] );
                    } else {
                        $next = "";
                    }
                    if ( $i == 0 && $this->inArrayNorm( $current, $this->titles ) ) {
                        $this->title = $current;
                        continue;
                    }
                    if ( $this->first == "") {
                        $this->first = $current;
                        continue;
                    }
                    if ( $i == $numSubPieces-2 && ($next != "") && $this->inArrayNorm( $next, $this->suffices ) ) {
                        if ( $this->middle != "") {
                            $this->middle .=  " ".$current;
                        } else {
                            $this->middle = $current;
                        }
                        $this->suffix = $next;
                        break;
                    }
                     if ( $i == $numSubPieces-1 && $this->inArrayNorm( $current, $this->suffices ) ) {
                                $this->suffix = $current;
                                continue;
                            }
                    if( $i == $numSubPieces-1 ) {
                        if ( $this->middle != "") {
                            $this->middle .= " ".$current;
                        } else {
                            $this->middle = $current;
                        }
                        continue;
                    }
                    if( $this->inArrayNorm( $current, $this->prefices ) ) {
                        if ( $this->middle == "") {
                             $this->middle .= " ".$current; 
                        
                        }//else{
                            //$this->middle = $current;
                        //} 
                        continue;               
                    } 
                    //display firstname without lastword of middle
                    if( $this->first != "" && $this->middle == "") {
                         $this->first .= " ".$current;
                    } 
                    
                 }
                if( isset($pieces[2]) && $pieces[2] ) {
                    if ( $this->last == "" ) {
                        $this->suffix = trim( $pieces[2] );
                        for ($s = 3; $s < $numPieces; $s++) {
                           //eto $this->suffix .= ", ". trim( $pieces[$s] );
                        }
                    } //else {
                       // for ($s = 2; $s < $numPieces; $s++) {
                       //     $this->suffix .= ", ". trim( $pieces[$s] );
                       // }
                   // }
                }
                $this->last = $pieces[0];
                break;
                }
                unset( $pieces );
                break;
        }
        if ( $this->first == "" && $this->middle == "" && $this->last == "" ) {
            $this->notParseable = TRUE;
        }
    }

}