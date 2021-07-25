import React from "react";
import { BrowserRouter as Router, Switch, Route, Link } from "react-router-dom";
import Layout from "./layout/Layout";
import Catalogue from './pages/Catalogue';

const App = () => {
	return (
		<div>
			{/* <Layout> */}
				<Catalogue />
			{/* </Layout> */}
		</div>
	);
};

export default App;
