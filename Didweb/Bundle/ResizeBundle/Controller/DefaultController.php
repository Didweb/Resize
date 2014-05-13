<?php

namespace Didweb\Bundle\ResizeBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use Symfony\Component\DependencyInjection\ContainerBuilder; 

class DefaultController extends Controller
{
	private $carpeta;
	private $ancho_p;
	private $alto_p;
	private $ancho_g;
	private $alto_g;
	
	
	
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



    public function getAbsolutePath(Container $container)
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir($container).'/'.$this->path;
    }

    public function getWebPath(Container $container)
    {
        return null === $this->path
            ? null
            : $this->getUploadDir($container).'/'.$this->path;
    }

    protected function getUploadRootDir(Container $container)
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir($container);
       
    }

    protected function getUploadDir($container)
    {	
		$this->container = $container;
        $carpeta = $this->container->getParameter('img_carpeta');
        return $carpeta;
    }


	public function CambioNombreImg(Container $container,$nombreViejo,$nombreNuevo)
	{
		$ruta	= $this->getUploadDir($container);
		$rutapV	= $this->getUploadDir($container)."/p/".$nombreViejo;
		$rutagV	= $this->getUploadDir($container)."/g/".$nombreViejo;
		
			if (file_exists($rutapV) && file_exists($rutagV) ) {
				rename($rutapV, $ruta.'/p/'.$nombreNuevo);
				rename($rutagV, $ruta.'/g/'.$nombreNuevo); }
		
		
	}

	public function eliminararchivo(Container $container,$nomruta)
		{
		$ruta=$this->getUploadDir($container)."/g/".$nomruta;
		$rutap=$this->getUploadDir($container)."/p/".$nomruta;
			if (file_exists($ruta)) {
				unlink($ruta);
				unlink($rutap); }
		}	




	public function upload(Container $container,$size_ancho,$size_alto,$ultimo)
		{
			$this->container = $container;
			$img_ancho_p 	= $this->container->getParameter('img_ancho_p');
			$img_alto_p 	= $this->container->getParameter('img_alto_p');
			$img_ancho_g 	= $this->container->getParameter('img_ancho_g');
			$img_alto_g 	= $this->container->getParameter('img_alto_g');
		    
		   if (null === $this->getFile()) {
			return;
		    }
			//--->> Sacamos extension
			$nombredelpath	= $this->getFile()->getClientOriginalName();
			
			
		
		    //Subimos el archivo con el nuevo nombre
		    $this->getFile()->move($this->getUploadRootDir($container).'/g/',$ultimo);
		
		   
			//$pImageOrigen=$this->getFile()->getClientOriginalName();
			$tmpname	= $this->getUploadRootDir($container)."/g/".$ultimo;	
			$save_dir_p	= $this->getUploadRootDir($container).'/p/';
			$save_dir_g	= $this->getUploadRootDir($container).'/g/';
			$save_name	= $ultimo;
			
			$this->img_resize( $tmpname, $img_ancho_p,$img_alto_p, $save_dir_p, $save_name );
			$this->img_resize( $tmpname, $img_ancho_g,$img_alto_g, $save_dir_g, $save_name );
		
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


public function borrarArchivos($container,$nomruta)
	{
	$ruta=$this->getUploadDir($container)."/g/".$nomruta;
	$rutap=$this->getUploadDir($container)."/p/".$nomruta;
			if (file_exists($ruta)) {
				unlink($ruta);
				unlink($rutap); }	
	return 0;
	}




}
