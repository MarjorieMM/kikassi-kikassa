import React from "react";
import { BrowserRouter as Router, Switch, Route, Link } from "react-router-dom";
import Layout from "./layout/Layout";

import { ShowObjects } from "./ShowObjects";

const App = () => {
	return (
		<div>
			<Layout>
			<ShowObjects />
			</Layout>
		</div>
	);
};

export default App;
