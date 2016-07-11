.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _linkedin:

Linkedin
============

Create a linkedin app on https://www.linkedin.com/developer/apps . From the app dashboard you will get Client ID and Client Secret. Give necessary permissions. Under OAuth2.0 add one authorized redirect url 'http://yoursite.com/typo3conf/ext/social_publisher/Resources/Public/files/link.php' (Replace yoursite.com with your domain name).

Enter the Client ID , Client Secret and the page id (Numerical Id of the linkedin page to which you want to share the typo3 page) in our extensions control panel. Save the settings. Click on the link 'Get Access Token' under Linkedin tab. Follw the steps, after this process the access token will pasted in to the control panel automatically. Save the settings again. 

You can enter a custom message on the field 'Message Text Format' which will be shared along with the page. By using some formatted texts you can include some page properties in the message. You will get the format informations for each social media by clicking on the link 'Show format info'.

You can verify the settings by clicking on the button 'Submit Test Post to Linkedin'.