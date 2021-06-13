import { Controller } from "stimulus";
import React from "react";
import ShowProducts from "../components/App";

export default class extends Controller {
	static values = {
		objets: Array,
		adherents: Array,
	};
	async connect() {
		const { default: ReactDOM } = await import("react-dom");
		ReactDOM.render(
			<ShowProducts
				objets={this.objetsValue}
				adherents={this.adherentsValue}
			/>,
			this.element
		);
	}
}
