// 'use strict';
//
// ((Survey) => Survey.ComponentCollection.Instance.add({
//   iconName: 'icon-visible',
//   name: 'projectvisibility',
//   title: 'Project visibility picker',
//   category: 'HeRAMS',
//   questionJSON: {
//     type: 'dropdown',
//     name: 'projectvisibility',
//     title: 'Project visibility',
//     allowClear: false,
//     required: true,
//     choicesByUrl: {
//       url: `/api-proxy/core/configuration/visibilities?_lang=${document.documentElement.lang}`
//     }
//   },
//   onInit () {
//     // debugger;
//   }
// }))(Survey)
import React, { useState, useEffect } from "react";
import { ElementFactory, Question, Serializer } from "survey-core";
import { SurveyQuestionElementBase, ReactQuestionFactory } from "survey-react-ui";
import { localization } from "survey-creator-core";
import DropdownInput from "../form/DropdownInput";
import { fetchProjectVisibilityChoices } from "../../services/apiProxyService";

const CUSTOM_TYPE = "projectvisibility";

// A model for the new question type
export class QuestionProjectVisibilityModel extends Question {
  getType() {
    return CUSTOM_TYPE;
  }
}

// Register `QuestionProjectVisibilityModel` as a model for the `projectvisibility` type
export function registerProjectVisibility() {
  ElementFactory.Instance.registerElement(CUSTOM_TYPE, (name) => {
    return new QuestionProjectVisibilityModel(name);
  });
}

const locale = localization.getLocale("");
locale.qt[CUSTOM_TYPE] = "Project Visibility";

Serializer.addClass(
    CUSTOM_TYPE,
    [],
    function () {
      return new QuestionProjectVisibilityModel("");
    },
    "question"
);

// A functional component that renders questions of the new type in the UI
export function SurveyQuestionProjectVisibility(props) {
  const [choices, setChoices] = useState([]);
  const question = props.question;

  useEffect(() => {
    async function loadChoices() {
      try {
        const fetchedChoices = await fetchProjectVisibilityChoices();
        setChoices(fetchedChoices);
      } catch (error) {
        console.error("Failed to load project visibility choices:", error);
      }
    }

    loadChoices();
  }, []);

  const handleChange = (e) => {
    question.value = e.target.value;
  };

  return (
      <DropdownInput
          options={choices}
          onChange={handleChange}
      />
  );
}

ReactQuestionFactory.Instance.registerQuestion(CUSTOM_TYPE, (props) => {
  return <SurveyQuestionProjectVisibility {...props} />;
});
