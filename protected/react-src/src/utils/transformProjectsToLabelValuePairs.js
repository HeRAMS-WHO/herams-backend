export const transformProjectsToLabelValuePairs = (projectList) => {
    return projectList.map(project => {
        const primaryLanguage = project.primary_language;
        const label = project.i18n['title'][primaryLanguage];
        const value = project.id;
        return { label, value };
    });
}
