<?php

namespace Didweb\Bundle\ResizeBundle\Acciones;

use Symfony\Component\DependencyInjection\ContainerBuilder; 
class Resize
{
	private $carpeta;
	private $ancho_p;
	private $alto_p;
	private $ancho_g;
	private $alto_g;
	private $container;
	private $file;
	private $ultimo;
	private $directorio;
	
	public function __construct($container = null)
	{
		$this->container = $container;
	}
	
    public function indexAction($name)
    {
		$this->carpeta 	= $this->container->getParameter('img_carpeta');
		$this->ancho_p 	= $this->container->getParameter('img_ancho_p');
		$this->alto_p 	= $this->container->getParameter('img_alto_p');
		$this->ancho_g 	= $this->container->getParameter('img_ancho_g');
		$this->alto_g 	= $this->container->getParameter('img_alto_g');	
        return $this->render('DidwebResizeBundle:Default:index.html.twig', 
							array(
							'name' 		=> $name,
							'carpeta'	=> $this->carpeta,
							'ancho_p'	=> $this->ancho_p,
							'alto_p'	=> $this->alto_p,
							'ancho_g'	=> $this->ancho_g,
							'alto_g'	=> $this->alto_g
							));
    }

	    public function ini($ultimo,$file)
    {
		
		
		$this->carpeta 	= $this->container->getParameter('img_carpeta');
		$this->ancho_p 	= $this->container->getParameter('img_ancho_p');
		$this->alto_p 	= $this->container->getParameter('img_alto_p');
		$this->ancho_g 	= $this->container->getParameter('img_ancho_g');
		$this->alto_g 	= $this->container->getParameter('img_alto_g');	
		$this->directorio = $this->container->getParameter('img_directorio');	
        $this->ultimo	= $ultimo;
        $this->file		= $file;
    }



	public function CambioNombreImg($nombreViejo,$nombreNuevo)
	{
		$ruta	= $this->directorio;
		$rutapV	= $this->directorio."/p/".$nombreViejo;
		$rutagV	= $this->directorio."/g/".$nombreViejo;
		
			if (file_exists($rutapV) && file_exists($rutagV) ) {
				rename($rutapV, $ruta.'/p/'.$nombreNuevo);
				rename($rutagV, $ruta.'/g/'.$nombreNuevo); }
		
		
	}



	public function borrarArchivos($nomruta)
		{
		$ruta  = $this->directorio."/g/".$nomruta;
		$rutap = $this->directorio."/p/".$nomruta;
				if (file_exists($ruta)) {
					unlink($ruta);
					unlink($rutap); }	
		return 0;
		}


	public function upload()
		{
			
		   
		   if (null === $this->file) {
			return;
		    }
			//--->> Sacamos extension
			$nombredelpath	= $this->file->getClientOriginalName();
			
		    //Subimos el archivo con el nuevo nombre
			$this->file->move($this->directorio.'/g/',$this->ultimo);
	
			$tmpname	= $this->directorio."/g/".$this->ultimo;	
			$save_dir_p	= $this->directorio.'/p/';
			$save_dir_g	= $this->directorio.'/g/';
			$save_name	= $this->ultimo;
			
			$this->img_resize( $tmpname, $this->ancho_p,$this->alto_p, $save_dir_p, $save_name );
			$this->img_resize( $tmpname, $this->ancho_g,$this->alto_g, $save_dir_g, $save_name );
		
		    // limpia la propiedad «file» ya que no la necesitas más
		    $this->file = null;
		}


	public function img_resize( $tmpname, $size_ancho,$size_alto, $save_dir, $save_name )
	    {
		$size = $size_ancho;	
	    $save_dir .= ( substr($save_dir,-1) != "/") ? "/" : "";
	    $gis       = GetImageSize($tmpname);
	    $type       = $gis[2];
	   
	    switch($type)
		{
		case "1": $imorig = imagecreatefromgif($tmpname); break;
		case "2": $imorig = imagecreatefromjpeg($tmpname);break;
		case "3": $imorig = imagecreatefrompng($tmpname); break;
		default:  $imorig = imagecreatefromjpeg($tmpname);
		}

		$x = imageSX($imorig);
		$y = imageSY($imorig);
		
			//if($gis[0]<$gis[1])
		if($gis[0] <= $size)
				{
				$av = $x;
				$ah = $y;
				}
				else
				{
				//aplicar el tamaño de alto en caso de que la foto sea mas alta que larga	
				if($gis[0]<$gis[1])
				{$size=$size_alto;}
				
				$yc = $y*1.3333333;
				$d = $x>$yc?$x:$yc;
				$c = $d>$size ? $size/$d : $size;
				$av = $x*$c;        
				$ah = $y*$c;        
				}  
		
		$im = imagecreate($av, $ah);
		$im = imagecreatetruecolor($av,$ah);
			//para fondo blanco
			$blanco = imagecolorallocate($im, 255, 255, 255);
			imagefill($im, 0, 0, $blanco);
			//fin para fondo blanco
	    if (imagecopyresampled($im,$imorig , 0,0,0,0,$av,$ah,$x,$y))
		if (imagejpeg($im, $save_dir.$save_name))
		    return true;
		    else
		    return false;
	    }







}
