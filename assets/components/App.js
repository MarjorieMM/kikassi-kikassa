import React from "react";
import { BrowserRouter as Router, Switch, Route, Link } from "react-router-dom";

import { ShowObjects } from "./ShowObjects";

export const App = () => {
	return (
		<div>
			<ShowObjects />
		</div>
	);
};
