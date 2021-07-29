import React from "react";
import ReactDOM from "react-dom";
import { HashRouter, BrowserRouter, Route, Switch } from "react-router-dom";
import Catalogue from "./pages/Catalogue";
import DetailsObject from "./pages/DetailsObject";
import Order from "./pages/Order";
import Layout from "./layout/Layout";
import Homepage from "./pages/Homepage";
import Fonctionnement from "./pages/Fonctionnement";
import Borrowings from "./users/Borrowings";
import Account from "./users/Account";
import CssBaseline from "@material-ui/core/CssBaseline";
import { createMuiTheme, ThemeProvider } from "@material-ui/core/styles";
import RalewayWoff2 from "../fonts/raleway-v19-latin-regular.woff2";
import RalewayWoff from "../fonts/raleway-v19-latin-regular.woff";
import RalewayEot from "../fonts/raleway-v19-latin-regular.eot";
import RalewayTtf from "../fonts/raleway-v19-latin-regular.ttf";

// If font doesn't work when in prod : see https://material-ui.com/customization/typography/
const raleway = {
	fontFamily: "Raleway",
	fontStyle: "normal",
	fontDisplay: "swap",
	fontWeight: 400,
	src: `
    local('Raleway'),
    local('Raleway-Regular'),
    url(${RalewayWoff2}) format('woff2')
    url(${RalewayWoff}) format('woff')
    url(${RalewayTtf}) format('truetype')
    url(${RalewayEot}) format('embedded-opentype')
  `,
	unicodeRange:
		"U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF",
};

const theme = createMuiTheme({
	spacing: 15,
	palette: {
		primary: {
			main: "#439b56",
		},
		secondary: {
			main: "#5594c0",
		},
	},
	typography: {
		fontFamily: "Raleway, Arial",
	},

	overrides: {
		MuiCssBaseline: {
			"@global": {
				"@font-face": [raleway],
			},
		},
	},
});

function App() {
	return (
		<div>
			<Switch>
				<Route
					path="/bibliotheque-objets/mon-compte/mon-historique"
					component={Borrowings}
				/>
				<Route path="/bibliotheque-objets/mon-compte" component={Account} />

				<Route
					path="/bibliotheque-objets/objet/:id/creation-emprunt"
					component={Order}
				/>
				<Route
					path="/bibliotheque-objets/objet/:id"
					component={DetailsObject}
				/>
				<Route
					path="/bibliotheque-objets/comment-ca-marche"
					component={Fonctionnement}
				/>
				<Route path="/bibliotheque-objets" component={Catalogue} />
				<Route exact path="/" component={Homepage} />
			</Switch>
		</div>
	);
}

ReactDOM.render(
	<React.StrictMode>
		<BrowserRouter>
			<ThemeProvider theme={theme}>
				<CssBaseline />
				<Layout>
					<App />
				</Layout>
			</ThemeProvider>
		</BrowserRouter>
	</React.StrictMode>,
	document.getElementById("root")
);
