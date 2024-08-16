<!DOCTYPE html>
<html lang="en-US">
 <head>
  <meta charset="utf-8">
 </head>
 <body>
  <div>
   <p>Hello,</p>
   <p>Your MissionNext Access has changed.</p>
   @if ($user['is_active'])
    <p>Access Granted</p>
    <p>IF A JOURNEY ORGANIZATION OR AN EDUCATION SCHOOL:</p>
    <p>We are pleased to inform you that your request for partnership with MissionNext has been approved!  You will soon have access to our online database of potential candidates to fill your open positions. Please watch for an email with payment instructions</p>
    <p>Once your payment has been confirmed, you will receive instructions on how to set up your profile and post your jobs in order to make the most of your MissionNext partnership.</p>
    <p>- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</p>
    <p>IF YOU APPLIED AS A JOURNEY AFFILIATE OR AN EDUCATION AFFILIATE: </p>
    <p>We are pleased to inform you that your MissionNext Affiliate Application has been approved!  You will soon be able to affiliate with your mission organization and/or schools to view their posted jobs and view candidate profiles.   </p>
    <p>Please watch for an email from MissionNext with instructions on how to proceed. </p>
    <p>If you have any questions, please contact us at headquarters@missionnext.org</p>
    <p>We look forward to partnering with you!</p>
    <p>MissionNext Partner Support Team</p>
   @else
    <p>Access Denied</p>
    <p>This could be because you signed up for the wrong website or wrong user type or have a duplicate record or you or your organization do not qualify for some reason.</p>
    <p>If you have any questions, please contact us at headquarters@missionnext.org  </p>
    <p>We look forward to partnering with you!  </p>
    <p>MissionNext Partner Support Team</p>
   @endif
  </div>
 </body>
</html>