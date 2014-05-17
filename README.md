Resize
======

Re-dimensionar imagenes

Bundle para redimensionar imágenes a dos tamaños "grande" y "pequeño"

Los tamaños y el director de destino son configurables con parametros desde el app/config.yml

## Instalación

Instalación mediante composer, poner en el archivo `composer.json` ...

```json
  "require": {

    ....
    
    "didweb/resize": "dev-master"
    
    ....
    
     }
```

En el archivo `app/AppKernel.php` poner poner...

```json
$bundles = array(
...
new Didweb\Bundle\ResizeBundle\DidwebResizeBundle(),
....
);

```


Dentro de `app/config/config.yml` poner la siguiente linea dentro de `imports` ...

```json

imports:
    - { resource: "@DidwebResizeBundle/Resources/config/services.yml" }

```


## Configurar

Para configurar se ponen los siguientes parametros dentro de `app/config/config.yml` ...

```json

didweb_resize:
    img_ancho_p: 240
    img_alto_p: 196
    img_ancho_g: 1024
    img_alto_g: 768
    img_directorio: %kernel.root_dir%/../web/fotos

```

### Detalles de configuración

En los parametros colocados en `app/config/config.yml` se especificán el ancho y alto tanto de la imagen tamaño grande como el tamaño pequeño, asi como el directorio del destino de las imágenes.

Los parametros `img_ancho_p` y `img_alto_p` hacen referencia al ancho y alto del tamaño pequeño en pixeles.

Los parametros `img_ancho_g` y `img_alto_g` hacen referencia al ancho y alto del tamaño grande en pixeles.

El parametro `img_directorio` se ha de especificar el destino d elas imágenes modifica "fotos" por el nombre de carpeta que quieras.


### Configurar directorio

Se ha de crear un nombre de carpeta en este caso hemos puesto "fotos" y dentro de el se han de crear 2 directorios más uno llamado `p` y otro `g`, son los directorios finales de las imágenes en `p` se almacenaran las de tamño pequeño y en `g` las de tamaño grande.


## Ejemplo de uso

Dentro del código en el lugar que quieras realizar la subida de archivo colocar esto ...

```json

			$resize = $this->get('didweb_resize.acciones');
      $resize->upload($nombreArchivo,$entity->getFile());

```


... donde `$nombreArchivo` es el nombre de archivo qu queremos poner y `$entity->getFile()` es el archivo subido mediante el formulario.


Ejemplo completo:


Dentro de tu controlador ....
```json
  
    public function createAction(Request $request)
    {
        $entity = new Imagen();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $entity->setSlug($entity->getNombre().'-'.rand(0,99999));
            $entity->setExtension();

            
            $em->persist($entity);
            $em->flush();

			      $resize = $this->get('didweb_resize.acciones');
            $resize->upload($entity->getSlug().'.'.$entity->getExtension(),$entity->getFile());


            return $this->redirect($this->generateUrl('imagen_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }


```

...un ejemplo de entidad Imagen ....

```json

<?php

namespace bancopruebas\BackendBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Imagen
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Imagen
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(name="orden", type="integer")
     */
    private $orden;


    /**
     * @var string
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;



    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=5)
     */
    private $extension;



    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

  /**
     * Set orden
     *
     * @param integer $orden
     * @return Imagen
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get orden
     *
     * @return integer 
     */
    public function getOrden()
    {
        return $this->orden;
    }


    
    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Imagen
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Imagen
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }



    /**
     * Set extension
     *
     * @param string $extension
     * @return Imagen
     */
    public function setExtension()
    {
		$nombredelpath=$this->getFile()->getClientOriginalName();
		$extension	=	explode(".",$nombredelpath);
		$corte		=	count($extension)-1;
		$extension	=	$extension[$corte];	
		
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        return $this->extension;
    }


	
		
}


```






