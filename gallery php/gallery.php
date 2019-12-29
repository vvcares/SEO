<?php 
         //echo "$directory"
        //get all files in a directory. If any specific extension needed just have to put the .extension
        $local = glob("" . $directory . "{*.jpg,*.JPG,*.gif,*.png}", GLOB_BRACE);
        //print each file name
        //echo "<ul>";
        foreach($local as $item)
        {
		echo '<li style="color:#fff;display: inline-block;">';
        echo '<a style="padding:1px 1px;" href="'.$item.'" data-lightbox="1">'.$item_name .'<img class="projects-style" src="'.$item.'" alt="Gallery"/></a>';
        }
        //echo "</ul>";
?>