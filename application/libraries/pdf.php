<?php defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;
class Pdf extends DOMPDF
{
    protected function ci()
    {
        return get_instance();
    }

    public function load_view($view, $data = array(),$stream=null)
    {
        $options = new Options();
        $options->set('defaultFont', 'dejavu sans');
        $dompdf = new Dompdf($options);
        $html = $this->ci()->load->view($view, $data, TRUE);
        $html1 = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        $dompdf->loadHtml($html1);
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A2', 'potrait');

        // Render the HTML as PDF
        $dompdf->render();
        $time = time();

        // Output the generated PDF to Browser
        $dompdf->stream('eomrah-invoice-no-'.$stream.$time);
    }
}