<?php
/* jaccard.php
 * Jaccard similarity coefficient calculator v1.0
 * by Jeb Ware (jeb@jebware.com)
 *
 * To use, pass two file names into the jaccard_similarity function and it will
 * return a jaccard similarity coefficient, between 0 and 1, inclusive.
 *
 * Note that each token present in the files is counted only once per file.
 * For example, file1 is "foo" 999 times and "bar" once. File2 is "foo" 999
 * times and "baz" once.  Since we ignore duplicates, there is only one word
 * in the union and three words in the intersection, so the similarity
 * coefficient will be 1/3.
 */

function jaccard_similarity($file1, $file2)
{
  //get an array of all of the tokens in each file
  $tokens1 = tokenize_file($file1);
  $tokens2 = tokenize_file($file2);
  //make an intersection array and a union array
  $inter = array_intersection($tokens1, $tokens2);
  $union = array_union($tokens1, $tokens2);
  //similarity coefficient is sizeof(intersection)/sizeof(union)
  $jac=0;
  if (sizeof($union) > 0)
    $jac = sizeof($inter)/sizeof($union);

  return $jac;
}

function tokenize_file($filename)
{
  $tokens = array();
  $ignoring = false;
  $farr = file($filename, FILE_IGNORE_NEW_LINES);
  foreach ($farr as $line)
  {
    $tok = strtok($line, " \n\t");
    while ($tok !== FALSE)
    {
      if ($ignoring) //we're in an HTML tag, look for a closing angle bracket
      {
        if (strpos($tok,">")!==FALSE)
          $ignoring=false;
      }
      else //we're not in a tag, look for an opening bracket
      {
        if (strpos($tok,"<")!==FALSE)
          $ignoring=true;
      }
      if (!$ignoring)
        $tokens[] = $tok;
      $tok = strtok(" \n\t");
    }
  }
  return $tokens;
}

/* returns an array containing every distinct element that occurs in both 
 * input arrays, without duplicates.
 */
function array_intersection($arr1, $arr2)
{
  $inter = array();
  sort($arr1);
  sort($arr2);
  $i1=0;
  $i2=0;
  while (($i1<sizeof($arr1)) && ($i2<sizeof($arr2)))
  {
    if ($arr1[$i1] == $arr2[$i2])
    {
      if ($arr1[$i1] != $inter[sizeof($inter)-1])
        $inter[]=$arr1[$i1];
      $i1++;
      $i2++;
    }
    else if ($arr1[$i1] < $arr2[$i2])
    {
      $i1++;
    }
    else if ($arr1[$i1] > $arr2[$i2])
    {
      $i2++;
    }
  }
  return $inter;
}

/* returns an array containing every distinct element that occurs in either
 * input array, without duplicates.
 */
function array_union($arr1, $arr2)
{
  $union = array();
  sort($arr1);
  sort($arr2);
  $i1=0;
  $i2=0;
  while (($i1<sizeof($arr1)) && ($i2<sizeof($arr2)))
  {
    if ($arr1[$i1]==$arr2[$i2])
    {
      if($arr1[$i1]!=$union[sizeof($union)-1])
        $union[]=$arr1[$i1];
      $i1++;
      $i2++;
    }
    else if ($arr1[$i1] < $arr2[$i2])
    {
      if ($arr1[$i1] != $union[sizeof($union)-1])
        $union[] = $arr1[$i1];
      $i1++;
    }
    else if ($arr1[$i1] > $arr2[$i2])
    {
      if ($arr2[$i2] != $union[sizeof($union)-1])
        $union[] = $arr2[$i2];
      $i2++;
    }
  }
  return $union;
}

//DEBUG here are some tests
return;
$arr1 = array('I', 'like', 'to', 'test', 'my', 'functions');
$arr2 = array('my', 'tests', 'are', 'my', 'functions');

print_r(array_intersection($arr1, $arr2));
print_r(array_union($arr1, $arr2));
?>
