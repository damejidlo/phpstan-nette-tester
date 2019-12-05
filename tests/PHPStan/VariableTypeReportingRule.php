<?php
declare(strict_types = 1);

namespace DamejidloTests\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Type\VerbosityLevel;



/**
 * @implements Rule<Variable>
 */
class VariableTypeReportingRule implements Rule
{

	public function getNodeType() : string
	{
		return Variable::class;
	}



	/**
	 * @param Node&Variable $node
	 * @param Scope $scope
	 * @return array<string|RuleError>
	 */
	public function processNode(Node $node, Scope $scope) : array
	{
		if (! is_string($node->name)) {
			return [];
		}

		if (! $scope->isInFirstLevelStatement()) {
			return [];
		}

		if ($scope->isInExpressionAssign($node)) {
			return [];
		}

		return [
			sprintf(
				'Variable $%s is: %s',
				$node->name,
				$scope->getType($node)->describe(VerbosityLevel::value())
			),
		];
	}

}
