<?php
declare(strict_types = 1);

namespace Spaze\PHPStan\Rules\Disallowed\Calls;

use PhpParser\Node;
use PhpParser\Node\Expr\Eval_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\ShouldNotHappenException;
use Spaze\PHPStan\Rules\Disallowed\DisallowedCall;
use Spaze\PHPStan\Rules\Disallowed\DisallowedCallFactory;
use Spaze\PHPStan\Rules\Disallowed\RuleErrors\DisallowedRuleErrors;

/**
 * Reports on dynamically calling eval().
 *
 * @package Spaze\PHPStan\Rules\Disallowed
 * @implements Rule<Eval_>
 */
class EvalCalls implements Rule
{

	/** @var DisallowedRuleErrors */
	private $disallowedRuleErrors;

	/** @var DisallowedCall[] */
	private $disallowedCalls;


	/**
	 * @param DisallowedRuleErrors $disallowedRuleErrors
	 * @param DisallowedCallFactory $disallowedCallFactory
	 * @param array $forbiddenCalls
	 * @phpstan-param ForbiddenCallsConfig $forbiddenCalls
	 * @noinspection PhpUndefinedClassInspection ForbiddenCallsConfig is a type alias defined in PHPStan config
	 * @throws ShouldNotHappenException
	 */
	public function __construct(DisallowedRuleErrors $disallowedRuleErrors, DisallowedCallFactory $disallowedCallFactory, array $forbiddenCalls)
	{
		$this->disallowedRuleErrors = $disallowedRuleErrors;
		$this->disallowedCalls = $disallowedCallFactory->createFromConfig($forbiddenCalls);
	}


	public function getNodeType(): string
	{
		return Eval_::class;
	}


	/**
	 * @param Eval_ $node
	 * @param Scope $scope
	 * @return RuleError[]
	 */
	public function processNode(Node $node, Scope $scope): array
	{
		return $this->disallowedRuleErrors->get(null, $scope, 'eval', 'eval', $this->disallowedCalls);
	}

}
