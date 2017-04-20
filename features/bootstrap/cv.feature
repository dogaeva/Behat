Feature: Creating and deleting CV on HH.ru

 
@debug 
  Scenario: User creates and deletes CV
	 When I login
	 Then I create CV
	 Then I delete CV