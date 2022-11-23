'use strict';

((Survey) => Survey.ComponentCollection.Instance.add({
  iconName: 'icon-visible',
  name: 'projectvisibility',
  title: 'Project visibility picker',
  category: 'HeRAMS',
  questionJSON: {
    type: 'radiogroup',
    name: 'projectvisibility',
    title: 'Project visibility',
    isRequired: true,
    choices: [
      {
        value: 'hidden',
        text: 'Hidden',
      },
      {
        value: 'public',
        text: 'Public',
      },
      {
        value: 'private',
        text: 'Private',
      },
    ],
  },
  onInit () {
    // debugger;
  },
}))(Survey)
