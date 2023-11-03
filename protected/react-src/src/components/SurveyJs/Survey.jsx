import React from 'react';
import SurveyWidget from './SurveyWidget';
import SurveyCreatorWidget from './SurveyCreatorWidget';

function Survey({ isCreatorMode }) {
    return (
        <div>
            {isCreatorMode ? (
                <SurveyCreatorWidget />
            ) : (
                <SurveyWidget />
            )}
        </div>
    );
}

export default Survey;
