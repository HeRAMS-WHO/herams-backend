# HeRAMS Data Model

This file attempts to describe the data model in use for HeRAMS.

## Terms

### Project
A project in HeRAMS is a ..., examples are **HeRAMS Nigeria** and **HeRAMS Sudan**.

### Workspace
Each project consists of several workspaces.
A workspace is managed by a workspace manager.
Examples include **Borno / Marte** and **Adamawa / Demsa**.

### Health facility
Each workspace manages (the reporting on) several health facilities.
A health facility is a facility that provides medical services.
Examples are the **Sundi Primary Health Post** in **Adamawa / Demsa** and the **Gumna Health Clinic** in **Borno / Marte **.
Information is gathered about the health facility as a whole, and about the services it provides.

[[NEEDS CONFIRMATION]]
Information about a health facility can be split into 2 categories: static and dynamic.
Static data are things that are not likely to change, like: name, type, modality and ownership.
Dynamic data are things where we actually want to measure changes, these include the condition of the building and whether the facility is functioning.

### Service
Each health facility may offer several services.
Per project a list of services is defined, for each health facility in the project the status of these services is reported.
Example services include: **Outpatient services**, **Intensive care unit** and **Diagnosis and treatment of malaria**.

For each service the availability and, when applicalbe, reasons for unavailability are reported.

## Data entry
Data entry other than initial project configuration is primarily done through LimeSurvey.
Each project has one survey that contains a response for each combination of health facility and reporting date.

To enable accurate data collection the data entry implemention has several abstract requirements:
- Don't repeat yourself (DRY); the system should require the user to implement identfying information and changes only.
- Simple; the system should not draw conclusions in case of data omission.

Consider a scenario where a health facility has 5 services that are all unavailable on day 1.

**Day 1**

| service | available |
| ------- | --------- |
| Service A | false |
| Service B | false |
| Service C | false |
| Service D | false |
| Service E | false |

On day 10 we are notified that since day 7 service C has been available.
In the current approach, we copy all data and get the information below.

**Day 1**

| service | available |
| ------- | --------- |
| Service A | false |
| Service B | false |
| Service C | false |
| Service D | false |
| Service E | false |

**Day 7**

| service | available |
| ------- | --------- |
| Service A | false |
| Service B | false |
| Service C | true  |
| Service D | false |
| Service E | false |


Then, on day 11 we are notified that someone forgot to inform us about
the fact that since day 3 services A and B have been available.
Entering this information is problematic, if we use the naive approach (copy and update)*[]:

**Day 1**

| service | available |
| ------- | --------- |
| Service A | false |
| Service B | false |
| Service C | false |
| Service D | false |
| Service E | false |

**Day 3**

| service | available |
| ------- | --------- |
| Service A | true  |
| Service B | true  |
| Service C | false |
| Service D | false |
| Service E | false |

**Day 7**

| service | available |
| ------- | --------- |
| Service A | false |
| Service B | false |
| Service C | true  |
| Service D | false |
| Service E | false |

Clearly, this is wrong. If services A and B were up since day 3 then
they should be available on day 7 as well.
As you can see, copying all data from the previous reporting date means
we're actually confirming that data as correct. While in reality we have
no new information and our goal was to only update the availability of 1 service.


[[TODO]] We should provide users with intuitive methods to the data they need to enter.
Below are example user stories

### Case: Health Facility property changes
- The user selects a reporting date
- The selects update type *health facility global*
- The user inputs the changes and clicks save.

The system should decide whether this is an update that needs to be merged with existing data for the same reporting date.
Alternatively the system could create a new data point containing the information entered by the user.

### Case: Service availability changes
- The user selects a reporting date
- The selects update type *health facility service*
- The user selects the service
- The user inputs the changes and clicks save.

### Gathering data in a time series
Data on health facilities and the services they provide is gathered together with a reporting date.
This facilities gathering multiple data points offline and later entering them into the system.

For example, consider someone in the field counting the number of beds every week:
```
Day  1: 10 beds
Day  8: 11 beds
Day 15: 12 beds
Day 22:  6 beds
Day 29: 30 beds
```

This data could be gathered offline, then on day 30 the data could be entered into the system while preserving the historical data.
Note that data need not be entered in chronological order.
Looking at the example above again, for example, on day 40 we could add new data and edit existing data:
```
Day 22: 10 beds
Day 23: 30 beds
```

This allows us to increase reporting accuracy as well as fix data entry or communication errors.


## Process for launching a new project
The process for launching a new HeRAMS project consists of several phases.

### Identify relevant services
The list of services on which information is gathered and reported is fixed for a project.
Since services are not universal we need to identify just the relevant services on which we want reporting for the new project.

### Identify workspaces
Since data collection is managed at a workspace level, the area that is covered by the project must be split into workspaces.

### Identify health facilities
TBD
Are all health facilities known at the start?
If not, can workspace managers add new ones? (Yes, via the response picker plugin?)


