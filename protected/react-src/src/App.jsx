
import useApp from "./hooks/useApp";
import Layouts from "./layouts";
const App = () => {
    const { Page, reactRoutes, layout } = useApp()
    return (
        <>
            <Router>
                <Layouts Page={Page} layout={layout} routes={reactRoutes} />
            </Router>
        </>
    )
};



export default App;
