<?php

//Canonicalize a URI. See the comments below to see what this means.
function uri_canonicalize($uri,$log_in_db=true)
{
  $input = $uri;
  $uri = urlencode($uri);
  //Optional: remove http and https scheme identifiers
  //$uri = str_replace("http://","",$uri,$count);
  //$uri = str_replace("https://","",$uri,$count);

  //downcase domain and protocol identifier
  $first_colon = strpos($uri,":");
  $first_slash = -1;
  if ($first_colon!==FALSE)
    $first_slash_offset = $first_colon+4;
  else
    $first_slash_offset = 0;
  $first_slash = strpos($uri,"/",$first_slash_offset);
  if (!($first_slash === FALSE))
  {
    $uri = strtolower(substr($uri,0,$first_slash)).
            substr($uri,$first_slash);
  }

  //remove reference to port 80 if it exists
  if ($first_colon !== FALSE)
    $second_colon_offset = $first_colon+1;
  else
    $second_colon_offset = 0;
  $second_colon = strpos($uri,":",$second_colon_offset);
  if ((!($second_colon === FALSE)) && ($second_colon < $first_slash)
      && (substr($uri,$second_colon,4)==":80/"))
  {
    $uri = substr($uri,0,$second_colon).substr($uri,$first_slash);
  }

  //remove fragment identifier
  $hash_position = -1;
  $hash_position = strpos($uri,"#");
  if (!($hash_position === FALSE))
    $uri = substr($uri,0,$hash_position);

  //remove trailing slash
  if (substr($uri,-1,1) == "/")
    $uri = substr($uri,0,-1);
  if (substr($uri,-3,3) == "%2F")
    $uri = substr($uri,0,-3);

  return $uri;
}

//If protocol isn't specified, add http:// as the protocol
function add_protocol($uri)
{
  $protopos = strpos($uri,"://");
  if ($protopos === FALSE)
    $uri = "http://".$uri;
  return $uri;
}

?>
