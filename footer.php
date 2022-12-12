<?php
   /* footer section */
   function proc_footer(array $options )
   {
         echo "<ul>";

         foreach ($options as $key => $value) {
               echo "<li>"."<a href='".$key."'>".$value."</li>";
         }

         echo "</ul>";

   } // end function footer()


   /**
    *  function footer (array $options)
    *
    *  @param  array $options
    *
                 $options = array ('key_1' => value_1, ..., 'key_n' => value_n);
    *       e.g. $options = array ('members.php' => 'Members only Area', 'logout.php' => 'Log out');
    *
    *  @returns string $footer_str with dynamically constructed <ul> list with hyperlinks
    *
    */


   function footer(array $options)
   {
         $footer_str = '';
         $footer_str.= "<ul>";

         foreach ($options as $key => $value) {
               $footer_str .= "<li>"."<a href='".$key."'>".$value."</li>";
         }

         $footer_str.= "</ul>";

         return $footer_str;

   } // end function footer()


?>

<!-- Test section //-->

<!--
<html>
<body>
    <?php

       $options = array ('members.php' => 'Members only Area', 'logout.php' => 'Log out');
       proc_footer ($options);


       echo "<br/>".' footer ($options) '."<br/>";

       $my_footer = footer($options);

       echo "$my_footer";

    ?>

    */

</body>
</html>
//-->
