# Swank - The web based Swagger spec file generator #
[Swagger](https://developers.helloreverb.com/swagger/) - Document your API with style

Swagger is best implemented as it was designed, to be a part of your code comments
and used to dynamically generate the Swagger spec file. Using it as designed 
follows the best practice of keeping your documentation and swagger sandbox 
environment in sync with your code.

That being said, sometimes you may be working in an environment where this is 
not possible. For example maybe you are creating APIs using a click-to-build 
system and there is no source code and build environment for you to insert your
comments describing the API. Or maybe you just want to create a cool Swagger 
interface for an existing API and you just want to create the spec file quickly
and your APIs don't change very often.

Personally at my work we have a few APIs that cannot support Swagger the way it
was designed, and I figure we cant be the only ones, so I created this tool to
help us all out in these situations.

## Swank Development ##
If you wish to contribute to Swank I would certainly appreciate the help. Simply
fork the project, do your work, and submit a pull request. I'll check it out and 
if it enhances the product for the masses, I'll pull it in.

## TODO ##
 - [x] Integrate [Yiistrap](http://www.getyiistrap.com) to simplify bootstrap styles with Yii widgets like a CGridView with a CActiveDataProvider
 - [ ] Create Public API Directory to list Applications with public status
 - [x] Implement support for Responses as part of Operations
 - [ ] Add support for Model/Object data types
 - [ ] Add ability to "Export" or "Download" standalone swagger-ui code
 - [x] Add ability to configure authentication type for an application, either token/key or none
 - [ ] Add support for oAuth authorization
 - [ ] Create new authentication option for signed tokens, example using a key and shared secret to calculate a signature to include in the call, I personally need this for use with [ApiAxle](http://apiaxle.com)
 - [x] Add ability to delete things, applications, apis, operations, parameters, responses, etc.
 - [ ] Add unit tests and setup with travis-ci

### Adding Authorization Options ###
Swagger supports three types of authorization right now: none, api_key, and oauth.

In order to support these in Swank, I've added support for none and api_key for now and will go back and add oauth at some point as well.

Adding support for another authorization type requires a few things to work properly in Swank:
1. Add the new option to Application::AUTHORIZATION_TYPES array
2. Create a new partial view to represent the form for providing configuration options. This goes into application/protected/views/partials/authorizations-configs/ and should be named after the type, example: api_key.php
3. The partial view should also define a javascript function named getAuthorizationConfig() which will return an object representing the configuration. When the form is submitted the object gets passed to PHP as an array which gets encoded as json before storage in database.
4. Update the ui/index.php view to add whatever swagger-ui code is necessary to support the authorization type