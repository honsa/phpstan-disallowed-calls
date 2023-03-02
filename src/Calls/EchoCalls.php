<?php
declare(strict_types = 1);

namespace Spaze\PHPStan\Rules\Disallowed\Calls;

use PhpParser\Node;
use PhpParser\Node\Stmt\Echo_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\ShouldNotHappenException;
use Spaze\PHPStan\Rules\Disallowed\DisallowedCall;
use Spaze\PHPStan\Rules\Disallowed\DisallowedCallFactory;
use Spaze\PHPStan\Rules\Disallowed\RuleErrors\DisallowedRuleErrors;

/**
 * Reports on dynamically calling echo().
 *
 * @package Spaze\PHPStan\Rules\Disallowed
 * @implements Rule<Echo_>
 */
class EchoCalls implements Rule
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
		return Echo_::class;
	}


	/**
	 * @param Echo_ $node
	 * @param Scope $scope
	 * @return RuleError[]
	 */
	public function processNode(Node $node, Scope $scope): array
	{
		return $this->disallowedRuleErrors->get(null, $scope, 'echo', 'echo', $this->disallowedCalls);
	}

}
