.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _facebook
Facebook
============

Create a facebook app as mentioned on https://developers.facebook.com/. You will get the 'Facebook App ID' and 'Facebook App Secret' from your app dashboard. To obtain the access token go to the graph api explorer tool (https://developers.facebook.com/tools/explorer/). 

From the first dropdown select your application and from the second drop down select 'Get user access token'. Give necessary permissions like 'publish_actions', 'manage_pages' etc. If you want to publish the typo3 page to your facebook page click on the second dropdown again and select your page name from there.


Copy the access token and goto access token tool https://developers.facebook.com/tools/accesstoken/ . On your app click on debug and paste the access token there click on debug button and then 'Extend access token' button. Use this access token in our extension.  

Enter the Facebook App ID, Facebook App Secret and Facebook Access Token in our extensions control panel and save the settings. You can enter a custom message on the field 'Message Text Format' which will be shared along with the page. By using some formatted texts you can include some page properties in the message. You will get the format informations for each social media by clicking on the link 'Show format info'. You can verify the settings by clicking on the button 'Submit Test Post to Facebook'.