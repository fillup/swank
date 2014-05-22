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
 - [ ] Integrate [Yiistrap](http://www.getyiistrap.com) to simplify bootstrap styles with Yii widgets like a CGridView with a CActiveDataProvider
 - [ ] Create Public API Directory to list Applications with public status
 - [ ] Implement support for Responses as part of Operations
 - [ ] Add support for Model/Object data types
 - [ ] Add ability to "Export" or "Download" standalone swagger-ui code
 - [ ] Add ability to configure authentication type for an application, either token/key or oAuth to start
 - [ ] Create new authentication option for signed tokens, example using a key and shared secret to calculate a signature to include in the call, I personally need this for use with [ApiAxle](http://apiaxle.com)
 - [ ] Add ability to delete things, applications, apis, operations, parameters, responses, etc.
