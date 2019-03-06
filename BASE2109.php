<?php


$debug = false;

$reload = false;
$offset = $_GET['offset'] ?? 0;
$limit = $_GET['limit'] ?? 20;

$linesProcessed = 0;
$currentLine = 0;
$file = fopen('BASE2109b.csv', 'rb');

while (($line = fgetcsv($file)) !== FALSE  && $linesProcessed < $limit) {
    if( $currentLine < $offset ) {
        continue;
    }
    $linesProcessed++;

    /*
    print_r($line);
    echo "<br/>";
    */



    $sqlString = "SELECT * FROM oxorderinvoice WHERE oxorderid = (SELECT oxid FROM oxorder where oxordernr ='".$line[0]."') AND status = 'success'";
    $result = $oConfig->execute($sqlString);

    echo $line[0]."<br/>";

    if( $result ) {

        $folios_count = 0;
        $folios_count_ok =0;
        $folios_count_error =0;

        $missing_folio = null;
        $missing_folio_index = null;
        $missing_folio_doc_type = null;
        $repeated_folio = null;
        $repeated_folio_doc_type = null;
        $last_folio_match = false;

        $folios = [];

        foreach ($result as $row) {
            //Check if folio is correct

            $dbFolio = $row['folio'];
            $xmlFolio = $invoiceCFDI->getFolioFromXML( $row['filename'] );

            echo "dbFolio: $dbFolio, xmlFolio: $xmlFolio<br/>";

            $folios[] = [ 'index1'=> $row['index1'] , 'db' => $dbFolio, 'xml' => $xmlFolio];
            $folios_count++;
            if( $dbFolio == $xmlFolio ) {
                $folios_count_ok++;
                $last_folio_match = true;

                if( null!==$missing_folio && null===$repeated_folio) {
                    $repeated_folio = $dbFolio;
                    $repeated_folio_doc_type = $row['doc_type'];
                }
            } else {
                $folios_count_error++;
                $last_folio_match = false;

                if( null=== $missing_folio ) {
                    $missing_folio = $dbFolio;
                    $missing_folio_index = $row['index1'];
                    $missing_folio_doc_type = $row['doc_type'];
                }
            }

            //Save relation currentFolio => correctFolio
            /*
            $resultInvoice = $invoiceCFDI->processStampedInvoice($row['filename'], true);
            echo "<pre>";
            var_dump($resultInvoice);
            echo "</pre>";
            */
        }

        if( $folios_count_error > 0 ) {
            if( false ) {
                echo "Scenario 4: Too many folios, manual review<br/>";
            } else {
                echo "folios_count: ". $folios_count ."<br/>";
                echo "folios_count_ok: ". $folios_count_ok."<br/>";
                echo "folios_count_error: ". $folios_count_error."<br/>";
                echo "missing_folio: ". $missing_folio ."<br/>";
                echo "missing_folio_doc_type: ". $missing_folio_doc_type ."<br/>";
                if( $repeated_folio) {
                    echo "repeated_folio: " . $repeated_folio . "<br/>";
                }
                if( $repeated_folio_doc_type) {
                    echo "repeated_folio_series: " . $repeated_folio_doc_type . "<br/>";
                }
                echo "last_folio_match: ". ($last_folio_match?'true':'false') ."<br/>";
                if( false !== $last_folio_match ) {
                    //Need to know if duplicated and missing folio have different document type, in those scenarios, just by changing the first folio to the duplicated one, solves it
                    if( $missing_folio_doc_type == $repeated_folio_doc_type ) {
                        //If same document_type, it needs a re-evaluation so we know how to balance the total invoiced
                        echo "Scenario 3a:<br/>";

                    } else {
                        //If not, just adjust folio numbers
                        echo "Scenario 3b:<br/>";
                        //Foreach wrong pair, correct.
                        foreach ( $folios as $folio) {

                            if( $folio['db'] != $folio['xml'] ) {
                                $oxorderinvoice = new \core\oxorderinvoice( $oConfig, $oCustomer );
                                $oxorderinvoice->load( $folio['index1'] );

                                $d = ['folio'=>$folio['xml']];
                                $oxorderinvoice->assign( $d, true );

                                if( $debug ) {
                                    echo "--------Correcting<br/>";
                                    echo "<pre>";
                                    print_r( $oxorderinvoice );
                                    echo "</pre>";
                                }
                            }
                        }
                    }

                } else {
                    if( null !== $repeated_folio ) {
                        echo "Scenario 2:<br/>"; //Doesn't exist
                    } else {
                        if( $folios_count == 1 ) {
                            echo "Scenario 1:<br/>";
                        } else {
                            echo "Scenario 1a:<br/>";
                        }
                        //Loads skipped folio from db
                        $oxorderinvoice_skipped = new \core\oxorderinvoice( $oConfig, $oCustomer );
                        $oxorderinvoice_skipped->load( $missing_folio_index );
                        $dummyData = [
                            'folio' => $missing_folio,
                            'oxorderid' => $oxorderinvoice_skipped->oxorderid,
                            'femcustomer' => $oxorderinvoice_skipped->femcustomer,
                            'filename' => '',
                            'doc_type' => $oxorderinvoice_skipped->doc_type,
                            'status' => 'do_not_process',
                            'hidden' => '1'
                        ];

                        //Foreach wrong pair, correct.
                        foreach ( $folios as $folio) {
                            $oxorderinvoice = new \core\oxorderinvoice( $oConfig, $oCustomer );
                            $oxorderinvoice->load( $folio['index1'] );

                            $d = ['folio'=>$folio['xml']];
                            $oxorderinvoice->assign( $d, true );

                            if( $debug ) {
                                echo "--------Correcting<br/>";
                                echo "<pre>";
                                print_r( $oxorderinvoice );
                                echo "</pre>";
                            }
                        }

                        //insert skipped folio as dummy record
                        $oxorderinvoice_dummy = new \core\oxorderinvoice( $oConfig, $oxorderinvoice_skipped->getCustomer() );
                        $oxorderinvoice_dummy->assign( $dummyData, true );

                        if($debug) {
                            echo "--------Dummy<br/>";
                            echo "<pre>";
                            print_r( $oxorderinvoice_dummy );
                            echo "</pre>";
                        }
                    }
                }
            }
        } else {
            echo "Case 5: Already corrected<br/>";
        }
    } else {
        echo "No rows found";
    }
}


fclose($file);
$_CRON_FINISHED           = !$reload;
