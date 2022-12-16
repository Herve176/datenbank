<?php


   /**
    *  class TableBuilder
    *
    *  dynamically generates a complete HTML-table as a string
    *
    *  @params  $widthTable - width of the HTML table to be built
    *           $widthColumn - width of each table column
    *
    *
    */
   class TableBuilder
   {
      private $widthTable = "600px";  /* width of the table */
      private $widthColumn = "200px"; /* width of the table columns : width is the same for all columns */
      private $table = "";            /* contains the generated HTML table as a string */
      private $table_begin = "";      /* HTML table tag with optional CSS style parameters */
      private $table_header = "";     /* HTML table header row containing all the column names */
      private $table_end = "";        /* HTML table end tag */
      private $table_rows = "";       /* string variable containing all constructed HTML table rows */
      private $rows = array();        /* array of rows, each element is a string containing a full HTML table row <tr> ... </tr> */
      private $table_body ="";        /* contains all the HTML table rows tags as a string */


     /**
      *  constructor for instances of class TableBuilder
      */
      public function __construct($widthTable, $widthColumn) {
           $this->widthTable = $widthTable;
           $this->widthColumn = $widthColumn;
           /* set the value of $this->table_begin */
           $this->beginTable();
           /* set the value of $this->table_end */
           $this->endTable();
      }

     /* appends <table> to $this->table */
     private function beginTable(){
          $style= "width: ". $this->widthTable."; border-collapse:collapse; border-width:3px; border-color:black; border-style:solid;";
          $this->table_begin .= "<table style ='".$style."' >";
       }

      /* construct a table header row
      *  @param $fields - array with the field values of the row to be built
      *          $bg_color - background color of the row
      *  appends a row to $this->table
      */
     public function buildTableHeader( array $fields , $bg_color){
         // $style= "width: ". $this->widthColumn."; border-width:5px; border-color: blue; background-color: #FFFF80; border-style:solid; padding:0px;";
         $this->table_header .= "<tr>";
         foreach($fields as $field) {
              // $bg_color = "#FFFF80";
              $style= "width: ". $this->widthColumn.";border-width:1px; border-color: blue; background-color:".$bg_color."; border-style:solid; padding:0px;";

             // style="width: 100px; border-width:1px; background-color: #FFFF80; border-style:solid; padding:0px;"
             $this->table_header .= "<th style ='".$style."' >" . $field ."</th>";
         }
         $this->table_header .= "</tr>"."\n";
     }


     /** construct a table row
      *  @param $fields - array with the field values of the row to be built
      *         $bg_color - background color of the row
      *  appends a row to $this->table
      */
     public function buildTableRow( array $fields , $bg_color ){
         $style= "width: ". $this->widthColumn."; border-width:0px; background-color: ".$bg_color."; border-color: black; border-style:solid; padding:0px;";
         $this->table_rows .= "<tr>";
         foreach($fields as $field) {
          //   $this->table .= "<td width='".$this->widthColumn."' >" . $field ."</td>";
             $this->table_rows .= "<td style ='".$style."' >" . $field ."</td>";
         }
         $this->table_rows .= "</tr>"."\n";
     }


     /** construct a table row
      *  @param $fields - array with the field values of the row to be built
      *         $bg_color - background color of the row
      *  appends a row to the array $this->rows
      */
     public function addRow( array $fields , $bg_color ){
         $style= "width: ". $this->widthColumn."; border-width:0px; background-color: ".$bg_color."; border-color: black; border-style:solid; padding:0px;";
         $row = "<tr>";
         foreach($fields as $field) {
            $row .= "<td style ='".$style."' >" . $field ."</td>";
         }
         $row .= "</tr>"."\n";

         /*append the row to the end of the array $this->table_body */
         array_push($this->rows, $row);
         // $this->rows[] = $row;
     }


     /** constructs the whole table body  containing all the table data rows
      *  merging the values of the array $this->rows
      *  into the single string variable $this->table_body
      *  by utilizing the php built-in array function implode(...)
      */
     private function buildTableBody ( ){
         $this->table_body = implode($this->rows);
     }



     /* appends </table> to $this->table */
     private function endTable(){
       $this->table_end .= "</table>";
     }

    /**
     * getter-method for $this->table attribute
     * @returns $this->table - the generated table as a string variable
     */
    public function getTable2() {
       $this->table .= $this->table_begin;
       $this->table .= $this->table_header;
       $this->table .= $this->table_rows;
       $this->table .= $this->table_end;

       return $this->table;
     }

    /**
     * build the whole HTML table as a string
     * and assign the value to $this->table attribute
     */
    private function buildTable() {
       $this->buildTableBody();
       $this->table .= $this->table_begin;
       $this->table .= $this->table_header;
       $this->table .= $this->table_body;
       $this->table .= $this->table_end;
     }



    /**
     * getter-method for $this->table attribute
     * @returns $this->table - the generated table as a string variable
     */
    public function getTable() {
       $this->buildTable();
       return $this->table;
     }



    /* static test function    */
    public static function test() {
         echo <<<TABLE_1
             <html>
                <head>
                   <title></title>
                </head>
                <body>
TABLE_1;


          $headers = array('id', 'type','name');

          $fields = array(
                      array('1', 'sheep','shaun') ,
                      array('2', 'dog','snoopy') ,
                      array('3', 'cat','pussy') ,
                      array('4', 'dolphin','flipper'),
                      array('5', 'cow','betsy')
                    );

          $t = new TableBuilder('450px', '150px');
          // $t->beginTable();
       // <font color="#C0C0C0">  <font color="#FFFFFF"></font>  <font color="#FFFFBB"></font>
          $bg_color = '#C0C0C0';
          $t->buildTableHeader($headers, $bg_color);

          $bg_colors = array('#FFFFFF','#E8EE0C');  // #F76510 #42EFAD    #06F9C0    #D4D0C8 #E8EE0C
          $odd = TRUE;
          foreach($fields as $field) {
              ($odd==TRUE)? $t->buildTableRow($field, $bg_colors[0]) :  $t->buildTableRow($field, $bg_colors[1]);
              ($odd== TRUE) ? $odd = FALSE :  $odd = TRUE;
          /*
              if($odd == TRUE) {
                 $t->buildTableRow($field, $bg_colors[0]);
              }
              if ($odd == FALSE) {
                 $t->buildTableRow($field, $bg_colors[1]);
              }

              if ($odd == TRUE) {
                  $odd = FALSE;
              }
              else {
                  $odd = TRUE;
              }
          */
          }

          // $t->endTable();

          echo $t->getTable();

     echo "</body></html>";

    }


        /* static test function    */
    public static function test2() {
         echo <<<TABLE_1
             <html>
                <head>
                   <title></title>
                </head>
                <body>
TABLE_1;


          $headers = array('id', 'type','name');

          $fields = array(
                      array('1', 'sheep','shaun') ,
                      array('2', 'dog','snoopy') ,
                      array('3', 'cat','pussy') ,
                      array('4', 'dolphin','flipper'),
                      array('5', 'cow','betsy')
                    );

          $t = new TableBuilder('450px', '150px');
       // <font color="#C0C0C0">  <font color="#FFFFFF"></font>  <font color="#FFFFBB"></font>
          $bg_color = '#C0C0C0';
          $t->buildTableHeader($headers, $bg_color);

          $bg_colors = array('#FFFFFF','#E8EE0C');  // #F76510 #42EFAD
          $odd = TRUE;
          foreach($fields as $field) {
              ($odd==TRUE)? $t->addRow($field, $bg_colors[0]) :  $t->addRow($field, $bg_colors[1]);
              ($odd== TRUE) ? $odd = FALSE :  $odd = TRUE;
          }

          echo $t->getTable2();

     echo "</body></html>";

    }




 } // end class  TableBuilder


  /* ---------------------------------------------------------------------- */
  /*    begin of test section
  /* ---------------------------------------------------------------------- */


    // TableBuilder::test();

   // echo "<br /><hr /><br />";

    // TableBuilder::test2();



?>