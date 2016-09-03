<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><title>Image To ASCII | By: Adrian Bece</title><link href="css/styles.css" rel="stylesheet"></head><header class=container><h1><span class=large-logo aria-hidden=true>██  ██      ██  ███████  ███████  ██████    ██████  ██████        ███████  ██████  ██████  ██  ██<br>██  ████  ████  ██   ██  ██       ██          ██    ██  ██        ██   ██  ██   █  ██  ██  ██  ██<br>██  ██  ██  ██  ███████  ██ ████  ████  ████  ██    ██  ██  ████  ███████    ██    ██      ██  ██<br>██  ██      ██  ██   ██  ██   ██  ██          ██    ██  ██        ██   ██  █   ██  ██  ██  ██  ██<br>██  ██      ██  ██   ██  ███████  ██████      ██    ██████        ██   ██  ██████  ██████  ██  ██<br></span><span class=screen-reader-content>Image To ASCII</span></h1></header><section class=container><article class='<?php if($_SERVER["REQUEST_METHOD"] == "POST") echo "hidden"; ?>'><p>This simple website allows users to upload an image from their computer and convert them to ASCII art. Selected JPEG image will be temporarly moved to "uploads" folder during the conversion process. PHP is used to analyse the image, convert it from RGB to Grayscale using Luma conversion algorithm and output the ASCII character for each pixel. For more information about ASCII art, check out <a href=https://en.wikipedia.org/wiki/ASCII_art title="Wikiepdia - ASCII art">this article on Wikipedia</a>.</p></article>
    <article class="box-input">
        <form enctype="multipart/form-data" method="post"
              action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' onsubmit="loadingMessage()">
            <?php

            function grayscale($rgb)
            {
                return round(0.3 * $rgb["red"] + 0.59 * $rgb["green"] + 0.11 * $rgb["blue"]);
            }

            function getIndex($gray)
            {
                return round($gray / 14);
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (!empty($_FILES['file'])) {
                    $target_path = "uploads/";
                    $target_path = $target_path . basename($_FILES['file']['name']);
                    // Temporarly upload a file to the server
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
                        $characterMap = ['#', '%', '8', 'M', 'w', 'p', 'k', 'a', 't', 'j', 'x', 'v', 'r', 'I', 'i', '_', ';', '^', '.'];
                        $img = @imagecreatefromjpeg($target_path);
                        $size = getimagesize($target_path);
                        $size_w = $size[0];
                        $size_h = $size[1];
                        $output = "";

                        if($size_h <= 1024 && $size_w <= 1024){
                            echo '<article class="box-output"><div><div><p>Converted File: '.$_FILES['file']['name'].'</p></div><label>Adjust Preview Scale: </label><input id="scaleImage" type="range" min="2" max="24" step="1" onchange="printValue(\'scaleImage\',\'asciiOutput\')" /></div>';
                            echo '<div><textarea class="ascii-output" id="asciiOutput" aria-hidden="true" spellcheck="false">';
                            for ($h = 0; $h < $size_h; $h++) {
                                for ($w = 0; $w < $size_w; $w++) {
                                    $rgb = imagecolorsforindex($img, imagecolorat($img, $w, $h));
                                    $output .= $characterMap[getIndex(grayscale($rgb))];
                                }
                                $output .= PHP_EOL;
                            }
                            // Output the results and close HTML tags
                            echo $output;
                            echo '</textarea></div><div class="box-success">Image conversion successful.</div></article>';
                        } else {
                            echo "<div class=\"box-error\">Maximum allowed resolution: 1024x1024 pixels. Please upload a smaller file.</div>";
                        }
                        // Cleanup - Delete uploaded file
                        unlink($target_path);
                    } else {
                        echo "<div class=\"box-error\">An error has occured. Please refresh and try again.</div>";
                    }
                }
            }
            ?>
            <div class="box-loading" id="loadingContainer">Uploading and ASCII-fying. Please wait. </div>
            <p>Upload and convert an image to ASCII art:</p>
            <ul class="box__input combined-container">
                <li>Select a .jpg image (Maximum width and height: 1024px)
                    <input class="box__file" type="file" name="file" id="file" accept="image/jpeg" required/>
                    <label for="file">Choose a File</label>
                    <span id="uploadFile"></span>
                </li>
                <li>Upload the selected image and let the magic happen
                    <button class="box__button" type="submit" onclick="clearContents()">Convert Image</button>
                </li>
                <li>Use the slider above the output to adjust the font scale and adjust the output box height for better preview</li>
            </ul>
        </form>
    </article>
</section><footer class=container>Made with &#128154; by <a href=https://hr.linkedin.com/in/adrianbece title="Adrian Bece - LinkedIn Page">Adrian Bece</a> for <a href=https://a-k-apart.com/ title="10K Apart">10K Apart</a>.</footer><script>function loadingMessage(){document.getElementById("loadingContainer").className+=" active"}function printValue(e,n){var t=document.getElementById(n),l=document.getElementById(e);t.style.fontSize=l.value+"px",t.style.lineHeight=l.value-1+"px"}document.getElementById("file").onchange=function(){document.getElementById("uploadFile").innerText=this.value}</script></html>