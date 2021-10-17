import {registerBlockType} from '@wordpress/blocks';

registerBlockType(
    'dsg/basic',
    {
        title:'Basic Block',
        description:'Este es nuestro primer bloque',
        icon:'smiley',
        category:'layout',
        edit: () => <h2>Hello World</h2>,
        save: () => <h2>Hello World</h2>

    }
)