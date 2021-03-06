<?php # -*- coding: utf-8 -*-
/*
 * This file is part of the inpsyde-validator package.
 *
 * (c) Inpsyde GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Inpsyde\Validator;

/**
 * Class LessThan
 *
 * @author  Christian Brückner <chris@chrico.info>
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @package inpsyde-validator
 * @license http://opensource.org/licenses/MIT MIT
 */
class LessThan implements ExtendedValidatorInterface {

	use ValidatorDataGetterTrait;
	use GetErrorMessagesTrait;

	/**
	 * @deprecated Error codes are now defined in Error\ErrorLoggerInterface
	 */
	const NOT_LESS = Error\ErrorLoggerInterface::NOT_LESS;

	/**
	 * @deprecated Error codes are now defined in Error\ErrorLoggerInterface
	 */
	const NOT_LESS_INCLUSIVE = Error\ErrorLoggerInterface::NOT_LESS_INCLUSIVE;

	/**
	 * @var array
	 * @deprecated
	 */
	protected $message_templates = [
		Error\ErrorLoggerInterface::NOT_LESS           => "The input <code>%value%</code> is not less than <strong>'%max%'</strong>.",
		Error\ErrorLoggerInterface::NOT_LESS_INCLUSIVE => "The input <code>%value%</code> is not less or equal than <strong>'%max%'</strong>."
	];

	/**
	 * @var array
	 */
	protected $options = [ ];

	/**
	 * @param array $options
	 */
	public function __construct( array $options = [ ] ) {

		// Whether to do inclusive comparisons, allowing equivalence to min and/or max
		$this->options[ 'inclusive' ] = isset( $options[ 'inclusive' ] )
			? filter_var( $options[ 'inclusive' ], FILTER_VALIDATE_BOOLEAN )
			: FALSE;

		$this->options[ 'max' ]      = isset( $options[ 'max' ] ) ? $options[ 'max' ] : 0;
		$this->input_data            = $this->options;
		$this->input_data[ 'value' ] = NULL;
	}

	/**
	 * @inheritdoc
	 */
	public function is_valid( $value ) {

		$this->input_data[ 'value' ] = $value;

		$inc   = $this->options[ 'inclusive' ];
		$valid = $inc ? $value <= $this->options[ 'max' ] : $value < $this->options[ 'max' ];
		$valid or $this->error_code = $inc
			? Error\ErrorLoggerInterface::NOT_LESS_INCLUSIVE
			: Error\ErrorLoggerInterface::NOT_LESS;
		$valid or $this->update_error_messages();

		return $valid;
	}

}