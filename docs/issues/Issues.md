#Issues

##Labels
All labels available: https://github.com/HeRAMS-WHO/herams-backend/labels

##Creating an issue
###Description
When creating an issue be descriptive as possible. A good approach is:

"I am logged in as `...` with `...`, and am on page `...`. I want to `...`"

From this description it should be clear for developers to reproduce the taken steps to get to the problem.
The desired next steps and features should be just as clear.

###Assigning labels
Labels must be assigned to make it very clear what the status of the issue is. Use the following issue categories:
* **env** Indicate on which platform the error occurs, not needed when it is a new feature  
* **urgency** When the urgency is other than normal, assign an urgency label like _extreme_, _high_ or _low_
* **subject** Help developers to filter issues to pick up quickly by adding a subject
* **type** Help developers prioritize issues withing an urgency, i.e. bug often is picked up before an enhancement

##Issue flow
###Assignee
The developers will assign the issues to themselves when they start working on an issue.

###Flow
* When there is **an assignee**, it can be assumed the issue is in progress
* When the implementation is complete and released on staging, a developer will add **flow:test on staging**
* It must then be checked by the reporter on staging:
    * When the issue is complete, add flow label **flow:works on staging**
    * When the issue is not complete, **add a comment** describing what is not working as expected and remove the label **flow:test on staging**
* When the issue has label **env:staging** the issue can now **be closed** by the developer
* When the developer releases the issue to **production**, he will add the label **flow:released**
* It must then be checked by the reporter on production:
    * When the issue is complete, **close the issue**
    * When the issue is not complete, add labels **env:production**, **type:bug** and **a comment** describing what is not working as expected  

