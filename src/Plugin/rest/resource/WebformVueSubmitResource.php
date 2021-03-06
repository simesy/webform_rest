<?php

namespace Drupal\webform_vue\Plugin\rest\resource;

use Drupal\webform\Entity\Webform;
use Drupal\webform\WebformSubmissionForm;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\rest\ModifiedResourceResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Creates a resource for submitting a webform.
 *
 * @RestResource(
 *   id = "webform_vue_submit",
 *   label = @Translation("Webform Submit"),
 *   uri_paths = {
 *     "canonical" = "/webform_vue/submit",
 *     "https://www.drupal.org/link-relations/create" = "/webform_vue/submit"
 *   }
 * )
 */
class WebformVueSubmitResource extends ResourceBase {

  /**
   * Responds to entity POST requests and saves the new entity.
   *
   * @param array $webform_data
   *   Webform field data and webform ID.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The HTTP response object.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws HttpException in case of error.
   */
  public function post(array $webform_data) {

    // Basic check for webform ID.
    if (empty($webform_data['webform_id'])) {
      $errors = [
        'error' => [
          'code' => '500',
        ],
      ];
      return new JsonResponse($errors, 500);
    }

    // Convert to webform values format.
    $values = [
      'webform_id' => $webform_data['webform_id'],
      'entity_type' => NULL,
      'entity_id' => NULL,
      'in_draft' => FALSE,
      'uri' => '/webform/' . $webform_data['webform_id'] . '/api',
    ];

    // Don't submit webform ID.
    unset($webform_data['webform_id']);

    $values['data'] = $webform_data;

    // Check for a valid webform.
    $webform = Webform::load($values['webform_id']);
    if (!$webform) {
      $errors = [
        'error' => [
          'message' => 'Invalid webform_id value.',
        ],
      ];
      return new ModifiedResourceResponse($errors);
    }

    // Check webform is open.
    $is_open = WebformSubmissionForm::isOpen($webform);

    if ($is_open === TRUE) {
      // Validate submission.
      $errors = WebformSubmissionForm::validateValues($values);

      // Check there are no validation errors.
      if (!empty($errors)) {
        $errors = ['error' => $errors];
        return new ResourceResponse($errors);
      }
      else {
        // Return submission ID.
        $webform_submission = WebformSubmissionForm::submitValues($values);
        return new ModifiedResourceResponse(['sid' => $webform_submission->id()]);
      }
    }
  }

}
