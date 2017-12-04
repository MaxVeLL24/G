<?php
    /*
     Copyright (c) 2013 ReFri Software / Internet Publication
 All rights reserved.

 Redistribution and use in source and binary forms, with or without
 modification, are permitted provided that the following
 conditions are met:

    * Redistributions of source code must retain the above copyright notice,
      this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice, this
      list of conditions and the following disclaimer in the documentation and/or
      other materials provided with the distribution.
    * Neither the name of ReFri Software / Internet Publicion nor the names of its contributors
      may be used to endorse or promote products derived from this software without
     specific prior written permission.

 THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
 EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL
 THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.


 youtube.php
 Author : Cynthia Fridsma 
 http://www.heathernova.us

 Date  : February 4, 2013

 Version: 1.1
 Property's width and height are now private
 Added:  variable error message

*/
    class YouTube {

    public $video;
    private $width;
    private $height;
    public $error = "<b>Video not found, sorry</b>" ;

    function __construct ($width=640, $height=360){

        $this->width=$width;
        $this->height=$height;      

        if($this->width<220){
            // minimum width
            $this->width=220;
        }
        if($this->height<220){
            // minimum height
            $this->height=220;
        }

    }    

    function playVideo(){

        // Default video settings:
        #
        # youtube_list = false
        # youtube_video = false

        $youtube_list = false;    
        $youtube_video = false;    

        // always check contains youtube.com or youtube.be as a reference....

        if(stristr($this->video, "youtube.com")==true ||(stristr($this->video, "youtube.be") || (stristr($this->video, "youtu.be")))){

            // Test if the video contains a query..

            $test = (parse_url($this->video));

            if(isset($test['query'])){

                $testing = $test['query'];
                parse_str($testing);
                if(isset($v)&&(isset($list))){                    

                    // we're dealing with a play list and  a selected video.                    
                    $test = $list;
                    $youtube_list = true;                      
                }
                if(isset($list) &&(empty($v))){
                    // we're only dealing wih a play list.
                    $test = $list;

                    $youtube_list = true;      
                    $test = $list;

                }
                if(isset($v) &&(empty($list))){
                    // we're only dealing with a single video.

                    $test = $v;
                    $youtube_video = true;    
                }
                if(empty($v) &&(empty($list))){
                    // we're not dealing with a valid request.
                    $youtube_video = false;                        
                }

            } else {

                // Apperently we're dealing with a shared link.

                $testing =parse_url($this->video, PHP_URL_PATH);

                $test = stristr($testing, "/");
                $test = substr($test,1);
                $youtube_video = true;    
            }

            if($youtube_video==true){
                    // Display a single video                                        

                    $play ='<iframe width="'.$this->width.'" height="'.$this->height.'" src="http://www.youtube.com/embed/'.$test.'?rel=0" frameborder="0" allowfullscreen></iframe>';
            }

            if($youtube_list==true){
                // Display a video play list.

                $youtube_video = true;

                $play = '<iframe width="'.$this->width.'" height="'.$this->height.'" src="http://www.youtube.com/embed/videoseries?list='.$test.'" frameborder="0" allowfullscreen></iframe>';

            }

                if($youtube_video == false){

                // We are unable to determine the video.

                $play = $this->error;
            }
        } else {

            // This is not a valid youtube requeust

            $play = $this->error;
        }

        // Return the results        

        return $this->playVideo=$play;
          }

      }
?>