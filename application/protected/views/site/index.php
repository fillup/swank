<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<div class="jumbotron">
    <div class="container">
        <h1>Swank - <br />Swagger spec file generator</h1>
        <p>
            <a href="https://developers.helloreverb.com/swagger/" target="_blank" 
               title="Swagger by Reverb">Swagger</a>
            is an awesome way to document your APIs with style. Swank 
            gives you a web based interface to create the Swagger spec files and 
            interact with your APIs.
        </p>
        <p><a class="btn btn-primary btn-lg" href="<?php echo Yii::app()->createUrl('/gen'); ?>">Get Started</a></p>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">What is Swagger?</h3>
            </div>
            <div class="panel-body">
                Swagger is a specification and complete framework implementation 
                for describing, producing, consuming, and visualizing RESTful 
                web services. The overarching goal of Swagger is to enable client 
                and documentation systems to update at the same pace as the server. 
                The documentation of methods, parameters, and models are tightly 
                integrated into the server code, allowing APIs to always stay in 
                sync. With Swagger, deploying managing, and using powerful APIs 
                has never been easier.
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">What is Swank?</h3>
            </div>
            <div class="panel-body">
                Swank is a web based tool for creating the Swagger spec files.
                Typically these spec files are generated dynamically by the Swagger
                client generators. This is the ideal way to use Swagger as it helps
                keep your documentation and interfaces in sync with the actual API.
                However, this is not always an option if you are working with a
                platform that does not have a client generator. In those cases,
                you can use Swank to manually create the client using a simple
                web interface.
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">More Information</h3>
            </div>
            <div class="panel-body">
                <strong>Swagger Documentation</strong>
                <ul>
                    <li><a href="https://developers.helloreverb.com/swagger/" 
                           target="_blank" title="Swagger Homepage">Swagger Homepage</a></li>
                    <li><a href="https://github.com/wordnik/swagger-spec" 
                           target="_blank" title="Swagger Specification">Specification</a></li>
                    <li><a href="https://github.com/wordnik/swagger-core/wiki/Downloads" 
                           target="_blank" title="Swagger Downloads">Downloads</a></li>
                </ul>
                <strong>Examples</strong>
                <ul>
                    <li><a href="http://petstore.swagger.wordnik.com/"
                           target="_blank" title="Swagger Petstore Demo">Swagger Petstore Demo</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

