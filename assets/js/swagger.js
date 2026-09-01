import '../vendor/swagger-ui/dist/swagger-ui.css';

import { SwaggerUIBundle, SwaggerUIStandalonePreset } from "swagger-ui-dist"
console.log(SwaggerUIBundle);

SwaggerUIBundle({
    dom_id: '#swagger',
    url: '/api/specification.yaml',
    presets: [
        SwaggerUIBundle.presets.apis,
        SwaggerUIBundle.SwaggerUIStandalonePreset
    ],
    //layout: "StandaloneLayout",
    displayOperationId: true,
    displayRequestDuration: true
});
