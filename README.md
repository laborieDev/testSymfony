# testSymfony

HOW TO INSTALL AND USE SYMFONY ?
LABORIE Anthony - 17.04.2020

Download composer.phar and install this on user/local
Try « composer -v »
Have PHP v7.2 or later
Create a new project with cmd composer 
	Here, use (composer create-project symfony/website-skeleton demo)
Install a personnal server for Symfony’s Project ( composer require symfony/web-server-bundle --dev ^4.4.2 )
Run this server (php bin/console server:run )


—
CONTROLLER

Make a new controller : (php bin/console make:controller)
Write this class’ name

This command create a new files ContollerName.php on Controller folder and a new folder with an index.html

For create a new page :

	Create a new function with this comment before : 

	/**
     	*  @Route("/nameofUrl", name="routeName")
     	*/

	Put your template :
	public function home(){
        		return $this->render('blog/home.html.twig');
   	 }

For add variants :
	
	 return $this->render('blog/home.html.twig', [
            ‘variantName’ => ‘variantValue’ ,
            ‘numberName’ => numberValue	
        ]);



—
TEMPLATES WITH TWIG

Use a PHP variant :
	{{ nameVariable }}

Use a PHP condition:
	{% condition %}

Make link to a other page :
	href="{{ path(‘nameRoute’) }}"

For add CSS or JS files on all pages :
	Add link tag or script tag on base.html.twig file

	For to have the bootstrap’s templates : https://bootswatch.com/

For extends from base file :
	{% extends 'base.html.twig' %} on top of file

	Don’t forget use the blocks (body, head, …) !!!

—
DATABASE : Doctrine —> ORM de Symfony

Set DATABASE_URL on .env file.

For create the database :
	php bin/console doctrine:database:create

For create table :
	php bin/console make:entity  
	
	After, put the name of table and there properties (name, type, length and null or not)

	Finally, make the migration ( php bin/console make:migration )

For add on this table a relation’s property :
	put relation like type’s property

For migrate all of database :
	php bin/console doctrine:migrations:migrate
	And confirm !

For to have DoctrineFixturesBundle (fake data for dev) :

	composer require orm-fixtures --dev

	AND
	
	https://github.com/fzaninotto/Faker

For to create a new Fixtures :
	php bin/console make:fixtures

	Edit this PHP’s file
	execute ( php bin/console doctrine:fixtures:load )

For to have all data of a table :

	$repo = $this->getDoctrine()->getRepository(Article::class);
	$object = $repo->findAll();

For to have data of object with 12 like numberID :
	$object = $repo->find(12);
 
For to have data of all objects with « Titre » like title :
	$object = $repo->findByTitle(‘Titre’);

For to have data of one object with « Titre » like title :
	$object = $repo->findOneByTitle(‘Titre’);


—
FORMS

For create a form :
	Controller : 
		$article = new Article();

        		$form = $this  ->createFormBuilder($article)
                     			->add('title')
                     			->add('content')
                     			->add('image')
                     			->getForm();

		return $this->render('blog/createArticle.html.twig', [
            				'formArticle' => $form->createView()
       			 ]);


	View :
		{{ form(formArticle) }}
		
		OR 

		{{ form_start(formArticle) }}

   		{{ form_widget(formArticle) }}

   		{{ form_end(formArticle) }}

		OR

	{ form_start(formArticle) }}

    	{{ form_row(formArticle.title, {'attr': {'placeholder':'Titre'}}) }}
    	{{ form_row(formArticle.content) }}
    	{{ form_row(formArticle.image) }}

    	<button type="submit" class="btn-primary btn">Ajouter</button>

    	{{ form_end(formArticle) }}

For create a form with Terminal :
	php bin/console make:form
	Add the form’s name (ArticleType for exemple)
	Add the Entity (Article for exemple)

	Controller :
		$form = $this->createForm(ArticleType::class, $article);

	View is same.


For to have bootstrap’s template for forms :

	Add bootstrap’s variant form_themes on config/packages/twig.yaml



Execute form :

	$form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){

            $manager = $mr->getManager();
            $manager->persist($article);
            $manager->flush();

	}

	// $mr is the ManagerRegistry


For add constraints on inputs :
	See https://symfony.com/doc/current/validation.html