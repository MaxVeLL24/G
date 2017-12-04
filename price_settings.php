<?

define('SHOW_QUANTITY',false); // true - show, false - hide quantity
define('USED_QUANTITY',false); // true - used quantity, false - not used quantity
define('SHOW_MARKED_OUT_STOCK',false); // show marked out of stock (true - show, false - hide)
define('TAX_INCREASE', 0); // 0 - No increase, 1 - Add 1%, 5 - Add 5%, Any number - add number%
define('SHOW_MODEL',false); // true - show model, false - hide model

define('FILE_NAME_PRICE','price'); //Any name file for price

//for *nix see http://www.ctan.org/tex-archive/tools/zip/info-zip/Zip.html
//define('PACKER','/home/work/zip -9'); // command line packer (zip lays in catalog)
define('PACKER','/usr/local/bin/zip -9');

?>