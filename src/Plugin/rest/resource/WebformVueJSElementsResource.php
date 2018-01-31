<?php

namespace Drupal\webform_vuejs\Plugin\rest\resource;

use Drupal\Core\Render\Element;
use Drupal\webform\Entity\Webform;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ModifiedResourceResponse;
use PHPUnit\Runner\Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Creates a resource for retrieving webform elements.
 *
 * @RestResource(
 *   id = "webform_vuejs_elements",
 *   label = @Translation("Webform Elements"),
 *   uri_paths = {
 *     "canonical" = "/webform_vuejs/{webform_id}/elements"
 *   }
 * )
 */
class WebformVuejsElementsResource extends ResourceBase {

  /**
   * Responds to GET requests, returns webform elements.
   *
   * @param string $webform_id
   *   Webform ID.
   *
   * @return \Drupal\rest\ResourceResponse
   *   HTTP response object containing webform elements.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws HttpException in case of error.
   */
  public function get($webform_id) {
    if (empty($webform_id)) {
      throw new HttpException(t("Webform ID wasn't provided"));
    }

    // Load the webform.
    $webform = Webform::load($webform_id);

    // Basic check to see if something's returned.
    if ($webform) {

      // Grab the form in its entirety.
      $form = $webform->getSubmissionForm();

      // Main components of the VueJS form.
      // Defines default values.
      $model = [];
      // Defines form elements.
      $schema = ['fields' => []];

      foreach (Element::children($form['elements']) as $element) {
        $field = $form['elements'][$element];

        switch ($field['#type']) {
          case 'textfield':
            $schema['fields'][] = [
              'type' => 'input',
              'inputType' => 'text',
              'label' => $field['#title'],
              'model' => $element,
              'readonly' => false, // Not sure where to get from webform yet.
              'required' => $field['#required'],
              'featured' => false, // Uncertain about this too.
              // '#placeholder' =>  "User's name",
              // '#validator' => 'VueFormGenerator.validators.string',
            ];
            $model[$element] = $field['#default_value'];
            break;

          case 'email':
            $schema['fields'][] = [
              'type' => 'input',
              'inputType' => 'text',
              'label' => $field['#title'],
              'required' => $field['#required'],
              'model' => $element,
            ];
            $model[$element] = $field['#default_value'];
            break;

          case 'textarea':
            $schema['fields'][] = [
              'type' => 'input',
              'inputType' => 'textArea',
              'label' => $field['#title'],
              'required' => $field['#required'],
              'model' => $element,
            ];
            $model[$element] = $field['#default_value'];
            break;

          case 'password':
            $schema['fields'][] = [
              'type' => 'input',
              'inputType' => 'password',
              'label' => $field['#title'],
              'required' => $field['#required'],
              'model' => $element,
            ];
            $model[$element] = $field['#default_value'];
            break;

          case 'select':
            $options = [];
            foreach ($field['#options'] as $key => $data) {
              // This currently isn't transforming the array.
              $options[] = [
                'id' => $key,
                'name' => (string)$data,
              ];
            }
            $schema['fields'][] = [
              'type' => 'select',
              'label' => $field['#title'],
              'values' => $options,
              'model' => $element,
            ];
            break;

          case 'checkbox':
            $schema['fields'][] = [
              'type' => 'checkbox',
              'label' => $field['#title'],
              'model' => $element,
            ];
            break;

          case 'webform_actions':
            foreach (Element::children($field) as $action) {
              switch ($field[$action]['#type']) {
                case 'submit':
                  $schema['fields'][] = [
                    'type' => 'submit',
                    'buttonText' => $field[$action]['#value'],
                    'model' => $element,
                  ];
              }
            }
            break;

          default:
            break;
        }

      }

      $response = [
        'model' => $model,
        'schema' => $schema,
        'formOptions' => [
          'validateAfterLoad' => true,
          'validateAfterChanged' => true,
        ],
      ];

      // Return only the form elements.
      return new ModifiedResourceResponse($response);
    }

    throw new HttpException(t("Can't load webform."));

  }


}
