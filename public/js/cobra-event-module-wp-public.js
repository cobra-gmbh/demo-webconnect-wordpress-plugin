// wp.blocks.registerBlockType('cobra/events-block', {
//     title: 'cobra CRM Event Liste',
//     icon: 'feedback',
//     category: 'design',
//     attributes: {
//         limit: { type: 'int' },
//         withImages: { type: 'boolean' }
//     },
//     edit: function (props) {

//         const { className, attributes, setAttributes } = props;
//         const { data } = attributes;

//         // function updateLimit(event) {
//         //     props.setAttributes({ limit: event.target.value })
//         // }

//         // function updateWithImage(event) {
//         //     props.setAttributes({ withImage: event.target.value })
//         // }
//         return (
//             <Fragment>
//                 <InspectorControls>
//                     <PanelBody title="Settings" initialOpen={false}>
//                         <ToggleControl
//                             label="Should text be shown?"
//                             help={show ? "Yes" : "No"}
//                             checked={show}
//                             onChange={() => setAttributes({ show: !show })}
//                         />
//                     </PanelBody>
//                 </InspectorControls>
//                 <div>
//                     My Block Content
//                 </div>
//             </Fragment>
//         );
//     },
//     save: function (props) {
//         return null;
//     }
// });
