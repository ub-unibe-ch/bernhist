require('swagger-ui/dist/swagger-ui.css');

const SwaggerUI = require('swagger-ui');

SwaggerUI({
    dom_id: '#swagger',
    url: '/api/specification.yaml',
    displayOperationId: true,
    displayRequestDuration: true
});
