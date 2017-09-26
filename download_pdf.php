<?
include("includes/session.php");
include("includes/db.php");
include("includes/funciones.php");

if(!$s_vip):
header('Location: /app/');
exit;
endif;

extract($_GET);
error_reporting(0);
$id_factura = limpiaStr($id_factura);

$sql="SELECT uuid FROM facturas_recibidas WHERE id_factura_recibida=$id_factura AND id_empresa=$s_id_empresa";

$q=mysql_query($sql);
$n = mysql_num_rows($q);
if(!$n) exit('nain');
$ft=mysql_fetch_assoc($q);
$nombre_pdf = 'PDF_'.$ft['uuid'].'.pdf';
$pdf= "archivos_pdf/$nombre_pdf";

$http = new imCurlBitch();  
$http->init();  
$url = "https://adminus.mx/app/formatos/factura_pdf_1.php?i=$id_factura&t=1&s_id_empresa=$s_id_empresa";


$content = $http->get($url);

if(file_exists($pdf)){

	header("Content-Type: application/pdf");
	header("Content-Transfer-Encoding: Binary");
	header("Content-disposition: attachment; filename=".$nombre_pdf);
	readfile($pdf);

}else{
	echo 'no exist';
}

/* clase CURL */
class imCurlBitch {
    private $curl;
    private $id;

    public function __construct() {
        $this->id = time();
    }

    public function init() {
        
        $this->curl=curl_init();
        
        curl_setopt($this->curl, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1");
        curl_setopt($this->curl, CURLOPT_COOKIEFILE,'cookies.txt');
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, 'cookies.txt');
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($this->curl, CURLOPT_AUTOREFERER, TRUE);
	}

    public function get($url) {
        $this->init();
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST,false);
        curl_setopt($this->curl, CURLOPT_REFERER, '');
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, TRUE);
        $result=curl_exec ($this->curl);
		/*  if($result === false){
            echo curl_error($this->curl);
        }*/
        
        $this->_close();
        return $result;
    }

    public function post($url,$data) {
        $this->init();

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST,true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, TRUE);
        $result=curl_exec ($this->curl);
        $this->_close();
        return $result;
    }

    private function _close() {
        curl_close($this->curl);
    }

}



